# Agile Guide

## Organization of Epics / Stories / Tasks

Work should be organized into Epics, Stories, and Tasks. These categories match the Acquia Jira configuration and many common set-ups.

### Epic

An Epic is a high level task such as "Product Ratings and Reviews". Depending on the project, the level of detail for an epic may vary. However, this can be seen as a category or larger task. Development work is not done directly against the Epic, it is just a place to describe the big picture.

An Epic is targeted at the client.

### Story

A Story is the basic unit of work for a project. Each Story should be independent in that it can be developed and tested by itself. Stories should also focus on the site user when possible. As such, each Story should define in a single short sentence exactly one thing that a site user would do, such as "As a consumer, when I view a product page, I can view product reviews."

The Story structure defines who is performing a task ("a consumer"), the context of the task ("when I view a product page"), and the task itself ("I can view product reviews").

A Story should focus on the task as outlined by the client. They should be descriptive and not proscriptive. A good rule of thumb is to ask, "Does this make any sense to a site consumer?".

Stories should also be written without specific technical requirements. Stories (and Acceptance Criteria) that are too technical are a red flag, because they often leave little room for the Acquia PS Team to provide guidance.

#### Acceptance Criteria

The Acceptance Criteria area of a story is the place for providing clarification of a Story. This should remain as user focused as possible and should be as brief as possible.

For example,

    h2. Story

    As a consumer, when I am on the homepage, I can log in to the site.

    h2. Acceptance Criteria

    - As a consumer, when I log into the site, my credentials are transmitted securely.
    - As a consumer, when I log into the site, if I enter incorrect credentials, I am notified that I need to try again.

In this example, bad Acceptance Criteria is too technical such as, "As a consumer, when I log into the site, I see a red asterisk to the right of the password input box that indicates the password field is a required field."

#### Testing Instructions

The testing instructions should be completed after development and when Stories are ready for User Acceptance Testing. These should always be provided so that clients can easily UAT the development work and so that there is a record of how that Story was actually completed.

### Tasks

Tasks solely for the development team. Clients should not even look at Tasks. They should be written with a technical focus to translate more user focused Stories into something a developer could use. Tasks are where the PS team can provide detailed implementation instructions.

Tasks should not be User Acceptance Tested. They can be QA'd by the development team, then marked as complete. The client should only be concerned with Stories.

# Example: Bug Report

    h2. Date
    -/--/2014

    h2. Versions
    *Browser Name and Version:* 7.x-1.x
    *OS Name and Version:* Chrome on OS X

    h2. Bug Description
    A concise description. Pure description, no narrative or conversational language.

    h2. Steps to Reproduce
    Step by step instructions on how to reproduce this bug. Screenshots, URLs and links are very useful.

    h2. Actual Behavior
    What happens when you follow the instructions. This is the manifestation of the bug.

    h2. Expected Behavior
    What you expected to happen when you followed the instructions. This is important, because there may be a misunderstanding on how something is implemented.

    h2. References
    - Chat Logs
    - Other descriptions of the bug

# Example: Story

    h2. Story

    As a consumer, when I view a product page, I can view product reviews.

    h2. Acceptance Criteria

    - As a consumer, when I am viewing a product review, I see a product rating (5 star system).
    - As a consumer, when I am viewing a product review, I see the authors nickname.

    h2. Testing Instructions

    - Go to /products/sample-product
    - Click 'Read Reviews'

    h2. Technical Guidance

    We will use a view to list "product review" nodes as a sorted list.

    h2. References
