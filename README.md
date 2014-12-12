# Codebase Repository Backup

## Installation and Setup

* clone the backup script repository
* run `composer install`
* create config file
* run the backup script

### Installation

    git clone git@github.com:mjaschen/codebase-backup.git
    cd codebase-backup
    composer install

### Setup

Create a config file: `cp config.php.template config.php`

Add config options in `config.php`:

* **CODEBASE_USER** your Codebse username, e. g. 'foobarinc/mjaschen', see "Settings > My Profile"
* **CODEBASE_TOKEN** Codebase API token, see "Settings > My Profile"
* **BACKUP_DIR** a backup target directory, e. g. `/Volumes/Backup/Codebase`
* **GIT_BIN** path to Git executable

### Run the backup script

    make backup

or

    php codebase_backup.php

## References

* [Codebase API Documentation](http://support.codebasehq.com/kb)
