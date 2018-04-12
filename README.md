# Plantuml website diagram scraper

> _"I'll play until they have to scrape me off the stage."_
> ~ James Young

Scrape all PlantUML diagrams from the PlantUML website.

## Introduction

This repository contains code with the soul purpose of extracting all PlantUML
diagrams from the [PlantUML website](https://plantuml.com/) that can be used as
test material for PlantUML Themes<sup>[1]</sup>.

## TL;DR

Use the already scraped diagrams in `build/diagrams/`

To generate them again:

- Run the project code
- See the project output

### Run the project code

    git clone https://github.com/potherca-blog/plantuml-website-diagram-scraper.git
    cd plantuml-website-diagram-scraper/
    composer install
    bash ./cli/run.sh ./build/

### See the project output

    tree -vFL 2 --dirsfirst

    .
    ├── build/
    │   ├── diagrams/               <─ 5. The ouput diagrams     ─> Used in PlantUML Themes
    │   ├── plantuml-images/        <─ 2. The downloaded images
    │   ├── plantuml.com/           <─ 1. The downloaded website
    │   └── diagrams.txt            <─ 3. The extracted diagrams
    ├── cli/
    │   └── run.sh
    ├── web/                        <─ 4. Compare images with diagrams
    │   ├── plantuml-diagrams.php
    │   └── plantuml-images.php
    └── README.md                   <─ 0. You are here

    6 directories, 5 files


## Installation

To install this project, download the source code and install the dependencies:

    git clone https://github.com/potherca-blog/plantuml-website-diagram-scraper.git
    cd plantuml-website-diagram-scraper/
    composer install

## Usage

The extraction is done taking the following steps:

1. Download the PlantUML **website** (so we only hit their servers once per page).
2. Dowload the **images** used in the website
3. Grab the **diagrams** from the local HTML pages
4. **Compare** the result of the diagrams with that of the dowloaded images
5. **Output** diagrams to separate files

The resulting diagrams can then be used as source for PlantUML Themes.

All of these steps can be executed by running the `cli/run.sh` shell script.

## Inner workings

### 1. Download the PlantUML website

The PlantUML website is downloaded using `wget`:

    # src/download_pages.sh
    wget                            \
        --convert-links             \
        --directory-prefix='build/' \
        --domains 'plantuml.com'    \
        --force-directories         \
        --html-extension            \
        --no-clobber                \
        --no-parent                 \
        --no-verbose                \
        --page-requisites           \
        --recursive                 \
        --wait=0.05                 \
        'plantuml.com'

### 2. Dowload the images

The images are grabbed from the HTML pages from local PlantUML website and
downloaded using `parallel` and `wget`:

    # src/download_images.sh
    find 'build/plantuml.com' -name '*.html' \
        -exec grep -R -a -P -o 'http://s.plantuml.com/img[pw]/[^"]+\.png' {} \+ \
        | cut -d':' -f2- \
        | parallel --gnu "wget --no-verbose --directory-prefix=build/plantuml-images {}"

### 3. Grab the diagrams

The diagrams are also grabbed from the HTML pages from local PlantUML website:

    # extract_diagrams.sh
    find  'build/plantuml.com' \
        -name '*.html' \
        -exec \
            grep -R -a -P -z -o \
                '(?s)(&#64;|@)startuml.+?@enduml' {} \+ \
        > "build/diagrams.txt"

### 4. Compare the result

By serving and surfing the content of the `web/` directory, it is possible to
compare the images as they they are used in the manual to the result of the
generated diagrams.

The easiest way to to this is using PHP's built-in webserver:

     php -S "${IP:=127.0.0.1}:${PORT:=8080}" -t ./web/

The images used in the manual are now available at: `http://${IP}:${PORT}/plantuml-diagrams.php`.

The result of the rendered diagrams are now available at: `http://${IP}:${PORT}/plantuml-images.php`

### 5. Output diagrams

Once the result has been verified, the diagrams can be ouput as individual files:

    php ./web/plantuml-diagrams.php

Please note that _only_ the files that are visible when visiting `http://${IP}:${PORT}/plantuml-diagrams.php`
will be stored as diagrams in `build/diagrams`.

### Done

The individual diagrams will now be available in `build/diagrams/`

---

## Footnotes

1. The PlantUML Themes project is yet to be open sourced.
   When it is, it will be linked from here.
