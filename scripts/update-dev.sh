# Fetch the Acquia repository.
git fetch acquia

# Fetch the Github repository.
git fetch upstream

# Push the upstream/dsb/develop branch to the Acquia respository
# git push [destination remote] [source remote]/[source branch]:refs/heads/[destination branch]
git push acquia upstream/develop:refs/heads/develop
