#Example of Acquia Hosting Cloud Hook to notify Slack of code deployments

Installation Steps (assumes Slack subscription setup and Acquia Cloud Hooks installed in repo):

* See the API documentation at https://api.slack.com/ get your TOKEN.
* Store this variable in $HOME/slack_settings file on your Acquia Cloud Server (see example file).
* Set the execution bit to on i.e. chmod a+x slack_settings
* Add slack.sh to dev, test, prod or common post-cody-deploy hook.


