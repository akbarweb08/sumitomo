require('dotenv').config({ path: '../.env' });
const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const cors = require('cors');

const app = express();
app.use(cors());
// Tambahkan middleware untuk parse JSON body
app.use(express.json());

const server = http.createServer(app);
const io = socketIo(server, {
    cors: {
        origin: "*", 
        methods: ["GET", "POST"]
    }
});

// Endpoint untuk menerima HTTP POST dari Laravel
app.post('/emit', (req, res) => {
    const channel = req.body.channel;
    const eventName = req.body.event;
    const eventData = req.body.data;

    console.log(`Received HTTP POST for channel ${channel}, event: ${eventName}`);
    
    if (channel && eventName) {
        // Broadcast ke semua client di Socket.IO
        io.emit(channel, {
            event: eventName,
            data: eventData
        });
        res.json({ success: true, message: "Event emitted" });
    } else {
        res.status(400).json({ success: false, message: "Missing channel or event" });
    }
});

io.on('connection', (socket) => {
    console.log('A user connected:', socket.id);
    
    socket.on('disconnect', () => {
        console.log('User disconnected:', socket.id);
    });
});

const PORT = process.env.SOCKET_PORT || 3000;
server.listen(PORT, () => {
    console.log(`Socket.IO server listening on port ${PORT}`);
});
