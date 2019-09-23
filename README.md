# CoWFC

Front- and back-end website for the DWC network server emulator

# CONTRIBUTING

Please open pull requests for the dev branch.

# How to Build
Note : This version of CoWFC is not compatible with the original dwc_network_server_emulator !

First, you will need to be running on Ubuntu. Otherwise the [setup script](https://github.com/EnergyCube/cowfc_installer) will not run. Please run the following command below to get started:

`mkdir /var/www ; cd /var/www && wget https://raw.githubusercontent.com/EnergyCube/cowfc_installer/master/cowfc.sh && chmod +x cowfc.sh && ./cowfc.sh`

If you're on AWS, you can run this instead:

`mkdir /var/www ; cd /var/www && wget https://raw.githubusercontent.com/EnergyCube/cowfc_installer/master/cowfc.sh && chmod +x cowfc.sh && && touch /var/www/.aws_install && ./cowfc.sh`

Your server will reboot after adding the PHP7.1 repo. After the server has rebooted, please issue the following commands:
`cd /var/www/ && ./cowfc.sh`

Follow the rest of the on-screen instructions and let your server build.

# Features
- Stats page shows who is online by game/country
- Admin panel to manage bans, whitelists, and more
- Directly edit the dwc_network_server_emulator settings

# Screenshots

## Login Page
![image](https://user-images.githubusercontent.com/10158714/30234202-09416e82-94c9-11e7-94ac-8aa6e8bf550d.png)
## Main Dashboard
![image](https://user-images.githubusercontent.com/10158714/30234212-212eadf2-94c9-11e7-8b01-24c10f67ce7a.png)
## User List - contains all the info an admin would need to identify a player
![image](https://user-images.githubusercontent.com/10158714/30234228-3f4ed5b4-94c9-11e7-814c-26d892d29707.png)
## dwc_network_server_emulator Settings
![image](https://i.ibb.co/WFGxYdZ/settings.png)
## Console Manual Activation
![image](https://i.ibb.co/GJd485w/consoles-waiting.png)

## More screenshots coming soon as we get further with development.

# TODO
- Integrate moderator rank system
- Integrate moderator account management portal
  - Only accessible by highest ranked moderators
  - Modification of users of the same rank must be done through shell
- Add more settings
- More ideas I'm sure we haven't thought of yet :p
