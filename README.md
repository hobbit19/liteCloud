<p align="center"> <a href="https://github.com/liteCloudRVA" target="_blank"> <img src="https://avatars1.githubusercontent.com/u/30016782?v=3&s=200"> </img> </a> </p>

![liteCloud_info](https://raw.githubusercontent.com/eorgiose/screenshots/master/HomeCloud.png)

[liteCloud](https://github.com/liteCloudRVA/liteCloud) :cloud: is a user management system for the server (home cloud). This application is suitable for everyone who has their own server and wants to structure their files on it, have easy access to their files from all devices, and write their own additional applications under liteCloud and use them.

# Getting Started

1. The minimum required PHP version 5, installed Apache2/nginx, MySQL.
2. In the local domain folder, clone the liteCloud.

        $ git clone https://github.com/liteCloudRVA/liteCloud

3. Install the database `xcloud_regedit.sql`.
4. Modify the ./resources/config.php file. Enter the data for the database into the `mysql` section, enter the path to the existing directory in the `path` section (within the directory the file manager will work).
5. Go to your local domain, the data for authorization `test:qwerty321`

# Information, Community

\\\\\\\\\\\ API, application errors, links. \\\\\\\\\\\
* Chief Developer **[RVA](https://github.com/rvasources)**, dev and translation of the application **[eorgiose](https://github.com/eorgiose)**.
* Available languages: English, Russian.
* Supported device types: Desktop, Mobile, Tablet.
# Standard Applications liteCloud

Initially 4 applications are preinstalled (system): 

![liteCloud_menu](https://raw.githubusercontent.com/eorgiose/screenshots/master/filesapp.png)

1. Settings - All information about the system and basic system settings are there.
2. Files - Browser files and directories, implemented the full functionality of the file manager.
3. Applications - In this category there are custom applications of the system.
4. Notifications - All system / application messages will be visible there.

From the list of applications, the APIs for editing are: Applications, Notifications. 

# Types of applications in liteCloud
###

There are 3 types of applications in liteCloud:
1. System - This type of application is displayed in the main menu. They are not edited and are not replaced.
2. Custom - Installed in the `Applications` section and launched as system applications.
3. Hidden - These applications work in the background. The application is managed in the `Notifications` tab.
4. Window - The operating principle is like a hidden application, but the application opens in the window. 

![liteCloud_files](https://raw.githubusercontent.com/eorgiose/screenshots/master/filesCloud.png)
