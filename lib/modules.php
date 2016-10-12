<?php

# helpers
Modules::AttachModule('Debug');
Modules::AttachModule('Calendar');
Modules::AttachModule('Benchmark');
Modules::AttachModule('Buffer');
Modules::AttachModule('Image');

#main
Modules::AttachModule('GoogleMaps');
Modules::AttachModule('Lang');
Modules::AttachModule('Url');
Modules::AttachModule('Page');
Modules::AttachModule('Menu');
Modules::AttachModule('Content');
Modules::AttachModule('ProductCatalogue');
Modules::AttachModule('Session');
Modules::AttachModule('Authorization');
Modules::AttachModule('Search');
    # Search::regClass('Content',          'search');
    Search::regClass('ProductCatalogue', 'search');
#modules
Modules::AttachModule('Banner');
Modules::AttachModule('Gallery');
Modules::AttachModule('SEO');
Modules::AttachModule('Tags');
Modules::AttachModule('Subscribe');
Modules::AttachModule('Shop');


Modules::AttachModule('IBlock');
Modules::AttachModule('Forms');