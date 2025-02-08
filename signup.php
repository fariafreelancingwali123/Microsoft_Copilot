const express = require('express');
const router = express.Router();
const bcrypt = require('bcrypt');
const User = require('./models/User'); // Importing the Mongoose User model

// Signup Endpoint
router.post('/signup', async (req, res) => {
    const { username, email, password } = req.body;

    try {
        // Validate input
        if (!username || !email || !password) {
            return res.status(400).json({ error: "All fields are required." });
        }

        // Check if user already exists
        const existingUser = await User.findOne({ email });
        if (existingUser) {
            return res.status(400).json({ error: "Email already in use." });
        }

        // Hash password and save user
        const hashedPassword = await bcrypt.hash(password, 10);
        const newUser = new User({
            username,
            email,
            password: hashedPassword,
        });

        await newUser.save();
        res.status(201).json({ message: "Registration successful!" });
    } catch (error) {
        console.error("Signup Error:", error);
        res.status(500).json({ error: "Registration failed. Please try again." });
    }
});

module.exports = router;
