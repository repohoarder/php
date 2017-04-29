#!/bin/bash

# This script will run on a remote server and take a few arguments passed thru to setup a build
# Author : Jamie Rohr
# Date   :  1/23/2013
#
# Variables #####################
# username
# password
# dbname
# dbuser
# path
# domain

        # initialize errors and other config variables
        export args=6
        export E_BADARGS=61
        export E_BADDIR=62
        export E_BADGZ=63

        if [ $# -ne $args ]
        then
         echo "Usage : $args expected"
         exit $E_BADARGS
        fi

        # initialize parameter variables
        export username=$1
        export password=$2
        export dbname=$3
        export dbuser=$6
        export path=$4
        export domain=$5
        export encryptpass=`echo -n $password | md5sum --text | sed 's/ *-$//'`

		
        # will need to add this to the usage arguments expected after done testing
        export rdbuser='thecreator'
        export rdbpass='h8TY8FjES5AWacPz'

        if [ ! -d $path ]
        then
         echo "Usage: $path does not exist<br>"
         exit $E_BADDIR
        fi


        if [ ! -f $path/website.tgz ]
        then
         echo "Usage $path/website.tgz does not exist<br>"
        exit $E_BADGZ
        fi

        # untar the tgz and remove it
        echo "changing directories to $path"
        cd $path

        echo "extracting $path/website.tgz<br>"
        tar -zxf $path/website.tgz

       
        echo "changing ownership to $username<br>"
        chown $username.$username $path -R
        chown $username.nobody $path
		
		echo "changing file permissions to 0644 and a+x on directories<br>"
		chmod a+x $path
        chmod 0644 $path -R
        find $path -type d -exec chmod a+x {} \;
        rm $path/website.tgz

        # check to see if there is a mysql file

        if [ -f $path/database.sql ]
        then
        # do some mysql shit here
        echo "dumping database now<br>"
        # drop database name and make user 
        mysql -u$rdbuser -p$rdbpass -e"drop database IF EXISTS $dbname"
        mysql -u$rdbuser -p$rdbpass -e"drop user $dbuser@localhost"
        mysql -u$rdbuser -p$rdbpass -e"create database $dbname"

		#mysql -u$rdbuser -p$rdbpass  -v -e"create user $dbuser@localhost identified by '$password'"
        #mysql -u$rdbuser -p$rdbpass -v -e"grant ALL PRIVILEGES on $dbname.* to $dbuser@localhost"
		#mysql -u$rdbuser -p$rdbpass -v -e"FLUSH PRIVILEGES"
		mysql -u$rdbuser -p$rdbpass -v  -e"grant ALL on $dbname.* to $dbuser@localhost identified by '$password'"
		echo "<br> Exit Code: $? <br>"
		
		#echo "grant ALL on $dbname.* to $dbuser@localhost identified by '$password';"
        echo "updating wordperss username and password<br>"
        # dump database now
        mysql -u$dbuser -p$password $dbname < $path/database.sql
       
        mysql -u$dbuser -p$password $dbname -e"update wp_options set option_value = '' WHERE option_name = 'bh_seo_link' LIMIT 1"
        mysql -u$dbuser -p$password $dbname -e"update wp_options set option_value = '' WHERE option_name = 'bh_seo_label' LIMIT 1"
		mysql -u$dbuser -p$password $dbname -e"update wp_users set user_login='$username' ,user_pass = MD5('$password') WHERE ID = 1"
        rm $path/database.sql
        fi

        echo "Finished<br>"
        exit 0
