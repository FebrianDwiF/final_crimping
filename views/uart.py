import serial
import serial.tools.list_ports
import asyncio
import websockets
import threading
import tkinter as tk
from tkinter import ttk, messagebox

PORT = None
BAUD_RATE = 115200
ser = None
gui_ref = None  # Referensi GUI global

# === GUI CODE ===
class SerialGUI:
    def __init__(self, master):
        global gui_ref
        gui_ref = self
        self.master = master
        self.master.title("Serial Port Connector")
        self.master.geometry("400x200")

        self.label = ttk.Label(master, text="Pilih Port:")
        self.label.pack(pady=5)

        self.port_combo = ttk.Combobox(master, values=self.get_serial_ports(), state="readonly")
        self.port_combo.pack(pady=5)

        self.connect_button = ttk.Button(master, text="Connect", command=self.connect_serial)
        self.connect_button.pack(pady=10)

        self.status_text = tk.Text(master, height=5, state="disabled")
        self.status_text.pack(fill="both", expand=True)

        self.master.protocol("WM_DELETE_WINDOW", self.on_close)

    def get_serial_ports(self):
        ports = serial.tools.list_ports.comports()
        return [port.device for port in ports]

    def connect_serial(self):
        global PORT
        selected_port = self.port_combo.get()
        if not selected_port:
            messagebox.showwarning("Peringatan", "Pilih port terlebih dahulu!")
            return
        PORT = selected_port
        self.append_status(f"🔌 Mencoba terhubung ke {PORT}...")
        threading.Thread(target=start_websocket, daemon=True).start()

    def append_status(self, msg):
        self.status_text.config(state="normal")
        self.status_text.insert("end", msg + "\n")
        self.status_text.see("end")
        self.status_text.config(state="disabled")

    def on_close(self):
        try:
            if ser and ser.is_open:
                ser.write(b"false\n")  # Pastikan LED mati saat aplikasi ditutup
        except:
            pass
        self.master.destroy()

# === SERIAL-WEBSOCKET CODE ===
RECONNECT_INTERVAL = 2

def connect_serial_only():
    global ser, PORT
    try:
        if ser is None or not ser.is_open:
            ser = serial.Serial(PORT, BAUD_RATE, timeout=1)
            print(f"🔌 Terhubung ke {PORT}")
            if gui_ref:
                gui_ref.append_status(f"✅ Terhubung ke {PORT}")
    except serial.SerialException as e:
        ser = None
        print(f"❌ Gagal menghubungkan ke {PORT}: {e}")
        if gui_ref:
            gui_ref.append_status(f"❌ Gagal menghubungkan ke {PORT}: {e}")

async def serial_to_websocket(websocket):
    global ser
    while True:
        connect_serial_only()
        if ser and ser.is_open:
            try:
                if ser.in_waiting:
                    response = ser.readline().decode(errors="ignore").strip()
                    if response:
                        print(f"📥 Dikirim ke Web: {response}")
                        await websocket.send(response)
            except serial.SerialException as e:
                print(f"⚠️ Serial error: {e}")
                ser = None
                await websocket.send("ERROR: Koneksi Serial terputus.")
        await asyncio.sleep(0.1)

async def websocket_to_serial(websocket):
    global ser
    try:
        async for message in websocket:
            print(f"📤 Dari Web: {message}")
            if ser and ser.is_open:
                try:
                    ser.write((message + "\n").encode())
                except serial.SerialException as e:
                    print(f"⚠️ Gagal kirim ke ESP32: {e}")
                    ser = None
                    await websocket.send("ERROR: Gagal kirim data ke ESP32")
    except websockets.exceptions.ConnectionClosed:
        print("🔌 WebSocket client memutuskan koneksi.")

async def handler(websocket):
    print("🟢 WebSocket client terhubung.")
    try:
        await websocket.send("INFO: WebSocket client terhubung.")
        await asyncio.sleep(2)

        task_send = asyncio.create_task(serial_to_websocket(websocket))
        task_recv = asyncio.create_task(websocket_to_serial(websocket))

        done, pending = await asyncio.wait(
            [task_recv, task_send],
            return_when=asyncio.FIRST_COMPLETED
        )

        for task in pending:
            task.cancel()

        print("🔌 Koneksi WebSocket ditutup.")

    except Exception as e:
        print("Terjadi error:", e)
        try:
            if websocket.open:  # ✅ Cek apakah koneksi masih terbuka
                await websocket.send(f"ERROR: Terjadi kesalahan: {e}")
        except Exception as inner_e:
            print("Gagal kirim error ke client karena koneksi sudah tertutup:", inner_e)

async def start_server():
    async with websockets.serve(handler, "localhost", 8765):
        print("🌐 WebSocket server berjalan di ws://localhost:8765")
        if gui_ref:
            gui_ref.append_status("🌐 WebSocket server aktif di ws://localhost:8765")
        await asyncio.Future()

def start_websocket():
    asyncio.run(start_server())

# === MAIN ===
if __name__ == "__main__":
    root = tk.Tk()
    gui = SerialGUI(root)
    root.mainloop()
