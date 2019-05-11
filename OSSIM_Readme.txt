AlienVault OSSIM (Instructions)
1.	Download the ISO File
2.	Make a new VM with a Debian 64-bit OS
3.	Give it 4 cores, 8 GB RAM, and 30GB Hard Disk Space
4.	Load the ISO on startup
5.	Pick Install OSSIM
6.	Set IP to 192.168.1.25 /24 since network is 192.168.1.0 /24 and Gateway 192.168.1.1
7.	Root Password Redwod413 and Another Account twk5@njit.edu with password Redwod413
8.	Bridge the Network Adapter with Gigabit Card or Wireless depending on what you are using.
9.	Configure Sensor
10.	Configure Data Source Plugins
11.	Enable ossim-agent
12.	Apply Changes and Reboot
13.	System Preferences
14.	Update AlienVault System
15.	Update System
16.	Save Changes and Reboot
17.	Go to HTTPS://192.168.1.25/ on any other machine
18.	Login
19.	Register Devices
20.	Test and Let run
21.	(I used my own home and devices as a test run)
22. Login to UI again user admin password Redwod413

On Client

1. sudo apt install libpcre2-dev zlib1g-dev
2. sudo PCRE2_SYSTEM=yes ./install.sh
3. agent
4. 192.168.1.25
5. keep hitting enter until it finishes
