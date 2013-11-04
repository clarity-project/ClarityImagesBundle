ClarityImagesBundle Emergency
==========================

Nice to see you learning our ClarityImagesBundle - saves media simple and flexible!

**Basics**

* [Installation](#installation)
* [Usage](#usage)

<a name="installation"></a>

## Installation

### Step 1) Get the bundle

#### Simply using composer to install bundle (symfony from 2.1 way)

    "require" :  {
        // ...
        "clarity-project/images-bundle": "dev-master",
        // ...
    }

You can try to install ClarityImagesBundle with `deps` file (symfony 2.0 way) like here -  [Symfony doc](http://symfony.com/doc/2.0/cookbook/workflow/new_project_git.html#managing-vendor-libraries-with-bin-vendors-and-deps), 
or with help of `git submodule` functionality - [Git doc](http://git-scm.com/book/en/Git-Tools-Submodules#Starting-with-Submodules).
But it's not tested ways! If you cat do it - just send approve to us, or fork and edit this documentation to solve our doubts =)

### Step 2) Register the namespaces

If you install bundle via composer, use the auto generated autoload.php file and skip this step.
Else you may need to register next namespace manualy:

``` php
<?php
// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Clarity\ImagesBundle' => __DIR__ . '/../vendor/clarity-project/images-bundle/Clarity/ImagesBundle',
    // ...
));
```

### Step 3) Register new bundle

Place new line into AppKernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Clarity\ImagesBundle\ClarityImagesBundle(),
    );
    // ...
}
```

<a name="usage"></a>

## Usage

Firstly you need to create FormType class and just use our form type as field:

``` php
<?php
// src/Acme/DemoBundle/Form/Type/DemoType.php

// ...
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('logo', 'clarity_image', array(
            'strategy' => 'acme_demo.form.strategy.demo_image',
            'required' => true,
        ))
        ->add('list', 'clarity_image', array(
            'strategy' => 'acme_demo.form.strategy.demo_image',
            'required' => true,
            'crop' => array(
                'enabled' => true,
                'strategy' => 'acme_demo.form.strategy.demo_image_crop',
                'width' => 300,
                'height' => 200,
            ),
        ))
    ;
}
// ... 
```

In your entity class you have no need to create additional not mapped fields such as logoFile or listFile. Only $logo and $list as strings for image uri.
If you are using `'clarity_image'` in collection type (for screenshots or something custom) please add option `'in_collection' => true`.

#### Upload & Crop strategies

###### Upload strategy register

``` php 
<?php
// src/Acme/DemoBundle/Form/Strategy/DemoImageStrategy.php

namespace Acme\DemoBundle\Form\Strategy;

use Clarity\ImagesBundle\Form\Strategy\AbstractCdnStrategy;

/**
 * 
 */
class DemoImageStrategy extends AbstractCdnStrategy
{
    /**
     * Name of the cdn storage to upload
     * 
     * {@inheritDoc}
     */
    public function getStorageName()
    {
        return 'image';
    }

    /**
     * Cdn container name. May be computed on fly
     * 
     * {@inheritDoc}
     */
    public function getContainerName()
    {
        return 'posts';
    }
}
```

Now we need just register our strategy class as service and thats all

``` xml
<service id="acme_demo.form.strategy.demo_image" 
    parent="clarity_images.form.strategy.abstract_cdn_strategy" 
    class="Acme\DemoBundle\Form\Strategy\DemoImageStrategy" 
/>
```

###### Crop strategy register

``` php
<?php
// src/Acme/DemoBundle/Form/Strategy/DemoImageCropStrategy.php

namespace Acme\DemoBundle\Form\Strategy;

use Clarity\ImagesBundle\Form\Strategy\AbstractCdnCropStrategy;

/**
 * 
 */
class DemoImageCropStrategy extends AbstractCdnCropStrategy
{
    // for now you have no abstract methods for redeclaring! It's so wonderfull, isn't it?! 
}

```

Register strategy as service

``` xml

<service id="acme_demo.form.strategy.demo_image_crop" 
    parent="clarity_images.form.strategy.abstract_cdn_crop_strategy" 
    class="Acme\DemoBundle\Form\Strategy\DemoImageCropStrategy" 
/>
```
