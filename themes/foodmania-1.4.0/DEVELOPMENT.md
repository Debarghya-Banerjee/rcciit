# Development Workflow

- Complete the features or bug fixes locally and push the branch to the GitHub repo.
- Raise a pull request (PR) against the `develop` branch and merge PR. Here, code review is not required.
- Raise a pull request (PR) against the `pre-release` branch after self testing and approval from QA team.
- Assign that PR to Developer/EM for internal code review. If any code review feedback, the team needs to address it and push changes, update PR, and re-request code review.
- Merge PR against `pre-release` branch once it's approved.
- Ask QA team for testing by comment on issue.

## Default Branch

`pre-release`

## Branch naming convention

- For feature - `feature/issue-name` For example, `feature/add-plugin`
- For bug - `fix/issue-name` For example, `fix/phpcs-errors`

## Pull Request and issue notes

- Title should be same as Issue title. Also add issue number before title. For example, `#3 Setup initial theme`.
- Add proper description.
- Assign reviewer and project.
- Create draft pull request for work in-progress PR and don't add `WIP:` in PR title.
- PR should have one approval.
