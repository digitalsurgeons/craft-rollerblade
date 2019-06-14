# Rollerblade plugin for Craft CMS 3.x

A simple plugint to use a regular old Asset source for your SVG Icons, but still have flexibility in inlining the output.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require digitalsurgeons/rollerblade

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Rollerblade.

## Using Rollerblade

In TWIG use:

    {{ rollerblade(entry.image.one(), 'some_css-class', {'data-custom-attr': 'value'}) }}

## Rollerblade Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [Digital Surgeons](https://digitalsurgeons.com)
