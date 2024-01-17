# Les différentes notions et fonctions abordées dans ce projet

## Partie 1

`asset()`: c'est une fonction twig qui permet d'avoir le chemin qui se trouve dans le dossier public. Donc que je mets asset() je suis directement dans le dossier public.

`path()`: c'est une fonction twig qui permet d'avoir le chemin vers une route.

`{% if loop first %}`: permet de savoir si c'est le premier élément de la boucle.

`{% if loop.last %}`: permet de savoir si c'est le dernier élément de la boucle.

`{{ dump() }}`: permet de faire un var_dump().

`{{ form_start() }}`: permet de commencer un formulaire.

`{{ form_end() }}`: permet de finir un formulaire.

`{{ form_widget() }}`: permet d'afficher les champs du formulaire.

`{{ form_row() }}`: permet d'afficher les champs du formulaire avec le label.

`{{ form_label() }}`: permet d'afficher le label du formulaire.

`{{ form_errors() }}`: permet d'afficher les erreurs du formulaire.

`{{ form_rest() }}`: permet d'afficher les champs du formulaire qui n'ont pas encore été affichés.


## EASYADMIN BUNDLE DE SYMFONY

`easyadmin` est un bundle de symfony qui permet de créer une interface d'administration.

`symfony console make:admin:dashbord`: permet de créer un dashboard.

`symfony console make:admin:crud`: permet de créer un CRUD sur une entité.

L'installation de easyadmin va nous créer un dossier `admin` dans le dossier `controller` qui va contenir un fichier `dashbordController.php`.

Ce fichier va contenir une classe `dashbordController` qui va hériter de la classe `AbstractDashbordController` qui va elle-même hériter de la classe `AbstractEasyAdminController`.

`AbstractDashbordController` va contenir une méthode `index()` qui va permettre d'afficher le dashbord.

**Méthode index()**

Dans cette méthode, nous avons `return parent::index();` qui va permettre d'afficher le dashbord par défaut.

Nous pouvons modifier le dashbord en mettant `return $this->render('admin/dashbord.html.twig');` à la place de `return parent::index();`. Dans ce cas, nous devons créer le fichier `dashbord.html.twig` dans le dossier `templates/admin`.

Nous avons aussi la possibilité de configurer la dashboard en utilisant `$adminUrlGenerator` qui est une instance de la classe `AdminUrlGenerator` qui va nous permettre de générer des urls.

Ou ` return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl())` qui va nous permettre d'afficher directement le CRUD de l'entité que nous voulons afficher.

On peut aussi afficher différents dashbord en fonction du rôle de l'utilisateur.

**Méthode configureDashboard()**

Cette méthode va nous permettre de configurer le dashbord.

Elle va nous permettre de changer le titre du dashbord, de mettre un logo, de mettre un lien vers le site, de mettre un lien vers la documentation, de mettre un lien vers la page de connexion, de mettre un lien vers la page de déconnexion,