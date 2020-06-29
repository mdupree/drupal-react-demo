# Drupal React Demo

### Setup

Once you have your drupal project setup you will want to setup some tooling

run `yarn init` from the project root.

Add the following lines to your package.json: 

```javascript
  "workspaces": [
    "web/modules/custom/*",
    "web/themes/custom/*"
  ]
```

This defines some "workspaces" making it easier to run commands across your modules and themes utilizing yarn.

Next you will need to create a module or theme. For my demonstration I create a module called `react_demo`

see `web/modules/custom/react_demo`

You will need to setup a few files within your module/theme:
- `package.json`
  ```javascript
    {
    "name": "react_demo_package",
    "version": "0.1.0",
    "private": true,
    "scripts": {
        // Dev will build a non-aggregated dev build.
        "dev": "npm run development",
        "development": "cross-env NODE_ENV=development webpack --progress --hide-modules --config=webpack.config.js",
        // Watch will build on any changes.
        "watch": "npm run development -- --watch",
        "hot": "cross-env NODE_ENV=development webpack-dev-server --inline --hot --config=webpack.config.js",
        // Build will create an aggregated production build. 
        "build": "npm run production",
        "production": "cross-env NODE_ENV=production webpack --no-progress --hide-modules --config=webpack.config.js"
    },
    "devDependencies": {
        "@babel/plugin-proposal-class-properties": "^7.8.3",
        "@babel/preset-react": "^7.9.1",
        "cross-env": "^7.0.2",
        "laravel-mix": "^5.0.4",
        "resolve-url-loader": "^3.1.0",
        "sass": "^1.26.9",
        "sass-loader": "^8.0.2"
    },
    "dependencies": {
        "react": "^16.13.1",
        "react-dom": "^16.13.1"
    }
    }

  ```
- `babel.rc` containing the following:
  ```javascript
  {
    "presets": ["@babel/preset-env", "@babel/preset-react"],
    "plugins":[
        "@babel/plugin-proposal-class-properties"
    ]
  }
  ``` 
- `webpack.config.js` && `webpack.mix.js` (See here for more details)[https://gist.github.com/moso/b98b8c0a65aedb46b3bfd3dbfebee1e9]

You are now setup to compile your react code.

Now you will add some directories to house your react code

Your module/theme should be similiar to the structure below:
```

/custom_module_dir
  |
  |- css
  |-- src
  |---- index.css
  |- js
  |-- src
  |---- App.js
  |---- index.js
  |- src
  |- .babelrc
  |- package.json
  |- webpack.config.js
  |- webpack.mix.js
  |- react_demo.info.yml

```

Add your react files.
- `custom_module_dir/js/src/App.js`
- `custom_module_dir/js/src/index.js`
- `custom_module_dir/css/src/index.css`

```javascript
// App.js
import React, { Component, Suspense } from 'react';

function App() {
  return (
      <div className="react-demo">
        <h1>
            Hello and welcome to react!
        </h1>
      </div>
  );
}

export default App;
```

```javascript
import React from 'react'
import ReactDOM from 'react-dom';
import App from './App';

// Note: getElementById contains the id of the div we will add
ReactDOM.render(
    <App />, 
    document.getElementById('react-demo-bind')
    ); 
```

Your directory structure should now look like so:
```
/custom_module_dir
  |- css
  |-- src
  |---- index.css
  |- js
  |-- src
  |---- App.js
  |---- index.js
  |- src
  |- .babelrc
  |- package.json
  |- webpack.config.js
  |- webpack.mix.js
  |- react_demo.info.yml
```

Now you have your basic react app setup.

Now we want to add our `react_demo.libraries.yml`
```yaml
custom:
  version: 1.0
  js:
    // Notice the dist folder. after build our app will be contained within the dist/ directory
    js/dist/index.js: { preprocess: false, minified: true }
  css:
    component:
      css/dist/index.css: { preprocess: false, minified: true }

// Attach React and React-Dom via CDN
react:
  js:
    //unpkg.com/react@16/umd/react.production.min.js:
      external: true
    //unpkg.com/react-dom@16/umd/react-dom.production.min.js:
      external: true
```

Next we need to add the mounting point (or the bind point if you will)

Create a block.
- `custom_module_dir/src/ReactDemoBlock.js`

```php
<?php

namespace Drupal\react_demo\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block for react demo.
 *
 * @Block(
 *   id = "react_demo_block",
 *   admin_label = @Translation("React Demo Block"),
 * )
 */
class ReactDemoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Add the mount point, this is a div with a specific ID we defined in index.js
    $build['#markup'] = '<div id="react-demo-bind"></div>';

    // Attach your library files.
    $build['#attached']['library'] = [
      'react_demo/react',
      'react_demo/custom',
    ];

    return $build;
  }

...

}

```

Your directory structure should now look like so:
```
/custom_module_dir
  |
  |- css
  |-- css
  |---- index.css
  |- js
  |-- src
  |---- App.js
  |---- index.js
  |- src
  |-- Plugin
  |---- Block
  |------ ReactDemoBlock.php
  |- .babelrc
  |- package.json
  |- webpack.config.js
  |- webpack.mix.js
  |- react_demo.info.yml
  |- react_demo.libraries.yml
```


Now from the root of your project run `yarn workspaces run build`