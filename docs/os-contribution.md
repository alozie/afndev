
# What not to release
- Use-case specific functionality (i.e., stuff no one else will really care about)
    - when in doubt, release
- Confidential or security sensitive information
    - definitions will depend upon applicable NDA or security agreements

# What to release

## Patches to core or contrib
- should always be contributed, if appropriate for public release
- make use of organization crediting

## Modules (or other)

### recommendation
- release to d.o
- team should select who should own the module based on contribution to it's creation and abiilty/interest in on-going ownership
    + ensure that maintainer transitioning is included in the process for off-boarding of staff
- make use of organization crediting
- benefits
    + greater community visibility 
    + able to make use of D.O packaging and testing bots
- drawbacks
    + organization does not have ownership of the module

### alternatives

#### organization ownership on D.O
- benefits
    + organization cannot loose control of the module
    + can serve NDA processes
- drawbacks
    + organization users are not really supported on D.O; requires assistance from Drupal Association staff

#### releasing on GitHub with D.O project page
- benefits
    + organization ownership is guaranteed
    + GitHub project flows (Pull Requests, etc.)
- drawbacks
    + frowned upon by D.O and part of community
- conderations
    + should issues be handled on D.O, GitHub, or both?
        * recommendation: issues should be tracked on GitHub to integrate best with the workfow


# Other factors
## Organization credit on d.o
- Engagement Managers should organizational credit participation with the client
- If the client is participating, client organization should be credited for all issues and modules released
- Otherwise, credit Acquia