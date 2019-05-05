IPTABLES Firewall

 

Install Steps:
sudo apt-get install iptables-persistent

Step 1: Deny All Connections
sudo iptables -P INPUT DROP
sudo iptables -P OUTPUT ACCEPT
sudo iptables -P FORWARD ACCEPT

Step 2: Allow Specific Connections
A.	DMZ both
sudo iptables -A INPUT -s 192.168.1.10 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.7 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.2 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.30 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.27 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.22 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.20 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.19 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.17 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.12 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.13 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.50 -j ACCEPT
B.	Broker Both
sudo iptables -A INPUT -s 192.168.1.10 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.7 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.2 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.30 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.27 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.22 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.20 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.19 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.17 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.12 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.13 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.50 -j ACCEPT
C.	Client Both
sudo iptables -A INPUT -s 192.168.1.10 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.7 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.2 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.30 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.27 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.22 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.20 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.19 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.17 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.12 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.13 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.50 -j ACCEPT

Step 3: Check the Rules and save:
Sudo iptables -S
OR
Sudo iptables -L
THEN
sudo netfilter-persistent save

Step 4: If using Ucarp or other Add IP’s
250 for the Broker ucarp
251 for the Client ucarp
252 for the DMZ ucarp

sudo iptables -A INPUT -s 192.168.1.250 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.251 -j ACCEPT
sudo iptables -A INPUT -s 192.168.1.252 -j ACCEPT

API is just general internet through port 80 or 443 so it doesn’t need an accept
60 for Nagios

sudo iptables -A INPUT -s 192.168.1.60 -j ACCEPT

70 for OSSIM

sudo iptables -A INPUT -s 192.168.1.70 -j ACCEPT



TROUBLESHOOTING:
If you mess up to reset everything:

sudo ip tables -P INPUT ACCEPT
sudo ip tables -P OUTPUT ACCEPT
sudo ip tables -P FORWARD ACCEPT

sudo ip tables -F
