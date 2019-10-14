# Découverte de Symfony 4

Mon besoin : 

- Un marketplace avec un système de login, des produits et des catégories 

![User](./assets/Capture%20d’écran%202019-10-14%20à%2011.22.53.png)

*User* 
- id 
- email
- password

*Category*
- nom

*Product*
- *user*
- nom
- description
- image
- *category*

### Utilisation de la ligne de commande Symfony 

> Création d'une entité utilisateur :

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.22.30.png)

> Mise à jour de la base de données :

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.24.47.png)

> Génération des routes /login /logout /register:

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.25.17.png)

> Création d'une entité category :

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.26.36.png)

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.28.04.png)

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.28.17.png)

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.28.26.png)

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.28.33.png)

> Création d'une entité Product :

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.29.04.png)

> Mise à jour de la base de données :

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.29.30.png)

> Génréation des CRUDs :

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.30.12.png)

> Génréation du formulaire d'enregistrement :

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.31.23.png)

### Choix d'un template 

> https://getbootstrap.com/docs/4.3/examples/album/

J'ai récupéré les assets et je l'ai ai ajouté au dossier : 

- /public/assets/js (pour les fichiers Js)
- /public/assets/css (pour les fichiers Css)

> Modification du base.html.twig : 

```html
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Julian">
    <title>{% block title %}IUT Marketplace{% endblock %}</title>

    {% block stylesheets %}
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="/assets/css/marketplace.css" rel="stylesheet">
    {% endblock %}
</head>
<body>

<main role="main">

    <div class="album py-5 bg-light">
        <div class="container">

            <div class="row">

                {% block body %}{% endblock %}

            </div>
        </div>
    </div>
</main>

{% block javascript %}
    <script>window.jQuery || document.write('<script src="/assets/js/jquery-3.3.1.slim.min.js"><\/script>')</script>
    <script type="text/javascript" src="/assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="/assets/js/jquery-3.3.1.slim.min.js"></script>
{% endblock %}

</body>
</html>

```

Ajout de mon menu :

```
...
<body>
<header>
    <div class="collapse bg-dark" id="navbarHeader">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                    <h4 class="text-white">Marketplace</h4>
                    <p class="text-muted">bla bla.</p>
                </div>
                <div class="col-sm-4 offset-md-1 py-4">
                    <h4 class="text-white">Menu</h4>
                    <ul class="list-unstyled">
                        {% if is_granted('IS_AUTHENTICATED_FULLY') %}
                            <li><a href="{{ path('app_logout') }}" class="text-white">logout</a></li>
                            <li><a href="{{ path('product_index') }}" class="text-white">Products</a></li>
                        {% else %}
                            <li><a href="{{ path('app_login') }}" class="text-white">login</a></li>
                            <li><a href="{{ path('app_register') }}" class="text-white">register</a></li>
                        {% endif %}

                        {% if is_granted('IS_AUTHENTICATED_FULLY') %}
                            <li><a href="{{ path('category_index') }}" class="text-white">Categories</a></li>
                        {% endif %}

                        {#
                        <li><a href="#" class="text-white">Like on Facebook</a></li>
                        <li><a href="#" class="text-white">Email me</a></li>
                        #}
                    </ul>
                </div>
            </div>
        </div>
    </div>
    ...
```

> Dans notre premier fichier /templates/default/index.html.twig : 

```
// ajouter la ligne suivante en haut du fichier

{% extends 'base.html.twig' %}
```

### Maintenant les problèmes : 

> aller sur http://127.0.0.1:8000/register 
> après la validation l'erreur suivante apparait : 

![User](./assets/Capture%20d’écran%202019-10-14%20à%2010.51.01.png)

Afin de corriger le problème il faut aller /src/Security/LoginFormAuthenticator.php : 

```
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }
```

Et ajouter dans ligne suivante dans /src/Controller/DefaultController.php : 

```
    /**
     * @Route("/", name="app_home")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'vars' => ['Hello World', 'Hello IUT']
        ]);
    }
```

### Uploader une image : 

https://symfony.com/doc/current/controller/upload_file.html