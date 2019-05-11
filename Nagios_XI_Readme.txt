Nagios XI Instructions

1. Download the OVA File off Nagios
https://www.nagios.com/downloads/nagios-xi/vmware/
2. Open in Virtual Box as-is
3. Startup
4. Give it the IP 192.168.1.60
5. Give a username and password


Client Installs
https://assets.nagios.com/downloads/ncpa/docs/Installing-NCPA.pdf
1. Download NCPA Client
2. Install off Linux Store
3. Change password through sudo vi /usr/local/ncpa/etc/ncpa.cfg
4. Restart Service "sudo systemctl restart ncpa_listener.service"
5. go to https://ip:5693

After Both Above are Done:
1. Go back to nagios 192.168.1.60
2. login nagiosadmin
2. password njit
3. configure tab
4. automatically search
5. click on ip you want to add.
6. go back to home and check host is on
