# Update local database script

This update script needs to be edited before being used. It assumes that aliases are created for a local environment and for an Acquia dev environment. The script can then be used to update the local database from the dev environment.

The benefit of this script is that the ordering of the database update is important or the dev server will be overwritten.

This also provides a quick way to distribute the update process amoung a team.

## Usage

`bash update-local-db.sh`

This must be run from your local environment.
