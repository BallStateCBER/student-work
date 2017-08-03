# Why Are We Here?

## Site objective:
Why Are You Here? will act as a repository of work for all the faculty, staff, and students who work or have worked for CBER. The site will showcase everything about what CBER employees do at and for CBER and Ball State/Muncie at large, including projects, publications, sites, designs, credentials, academic accomplishments, and community engagement. By consolidating all of our employees and their work into a neat little catch-all repository, Why Are You Here? will allow all of us at CBER to serve as professional and personal references for each other if and when we seek work elsewhere, if anyone wants to nominate any of us for any awards, writing bios for speaking engagements, and any number of things that would require us to know *why we’re here*.

## Design
The site will be strictly professional and for internal use, so the design will be simple, sleek, largely monochromatic, and with sans-serif fonts. I’ll design the layout, and build the front-end with Victoria’s guidance.

**Frameworks/libraries used:** [Bootstrap](https://v4-alpha.getbootstrap.com/), [jQuery](https://jquery.com/).

## Development
Essentially, Why Are We Here? is going to be a glorified MySQL interface built on [CakePHP](http://cakephp.org) 3.4. So the development is mostly going to be very query-heavy models, tidy views with simple layouts, and controllers responsible for basic actions: adding, indexing, that sort of thing. I’m sure as the site grows, the controllers will become more complex, and more features will be thought of.

## Features

### Profiles
1. Name, job title, start date (and, if applicable, last date), and birthday.
2. Photo and short bio (250 words or less).
3. Publications and how they’ve contributed.
4. Websites and how they’ve contributed.
5. Academic background, degrees, and other special training.
6. Awards or accolades received.
7. Community participation, organizations affiliated with, and engagements.
### Studies & publications
1. Title, abstract, and date published.
2. Front cover and link to the publication (if applicable).
3. Authors and any other contributors.
4. Other publications the study is referenced in.
5. Articles or media about the study.
6. Financial backers for the publication.
### Sites
1. Name of the site and date live.
2. Short site description (250 words or less) and a link to the site.
3. Web developers and any other contributors.
4. Other sites that link to the site.
5. Financial backers for the site.
### Reference generator
  * This will aggregate all the info in an employee’s profile into a tidy print-ready one-page document of bullet points for easy reference-giving.
