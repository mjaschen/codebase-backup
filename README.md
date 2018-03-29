# Codebase Repository Backup

[Codebase][] is “Git, Mercurial and Subversion hosting with project management tools”. 
It's used for hosting software projects, like Github or Gitlab.

Codebase provides an API which is leveraged by this tool for exploring all
of an account's repositories and backup them to a local folder.

## Installation and Setup

* clone the backup script repository: `git clone git@github.com:mjaschen/codebase-backup.git`
* change into the newly created directory: `cd codebase-backup`
* run `composer install --no-dev`
* create config file
* run the backup script

### Installation in one line

    git clone git@github.com:mjaschen/codebase-backup.git && cd codebase-backup && composer install --no-dev

### Setup

Create a config file: `cp config.php.template config.php`

Add config options in `config.php`:

* **CODEBASE_USER** your Codebse username, e. g. 'foobarinc/mjaschen', see "Settings > My Profile"
* **CODEBASE_TOKEN** Codebase API token, see "Settings > My Profile"
* **BACKUP_DIR** a backup target directory, e. g. `/Volumes/Backup/Codebase`
* **GIT_BIN** path to Git executable; optional

### Run the backup script

    make backup

or

    php codebase_backup.php

## References

* [Codebase API Documentation](http://support.codebasehq.com/kb)

[Codebase]: https://www.codebasehq.com/
