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

Elle va nous permettre de changer le titre du dashbord, de mettre un logo, de mettre un lien vers le site, de mettre un lien vers les traductions, de mettre un lien vers la documentation, de mettre un lien vers la déconnexion,


**Méthode configureMenuItems()**

Cette méthode va nous permettre de configurer le menu de navigation du dashbord.

- `yield MenuItem::linkToCrud('Dashboard', 'fa fa-home');` : permet d'afficher le nom du dashbord et l'icone du dashbord.

- `yield MenuItem::linkToCrud('Nom du Menu', 'ICONE FONT AWESOME', Entity::class);` : permet d'afficher le nom du menu, l'icone du menu et le CRUD de l'entité.

- `yield MenuItem::linkToCrud('Nom du Menu', 'ICONE FONT AWESOME', Entity::class)->setPermission('ROLE_ADMIN');` : permet d'afficher le nom du menu, l'icone du menu et le CRUD de l'entité seulement si l'utilisateur a le rôle ROLE_ADMIN.

- `yield MenuItem::linkToCrud('Nom du Menu', 'ICONE FONT AWESOME', Entity::class)->setPermission('ROLE_ADMIN')->setController(OneOfYourCrudController::class);` : permet d'afficher le nom du menu, l'icone du menu et le CRUD de l'entité seulement si l'utilisateur a le rôle ROLE_ADMIN et d'afficher le CRUD de l'entité que nous voulons afficher.


La commande `symfony console make:admin:crud` va nous créer un fichier `EntityCrudController.php` dans le dossier `controller/admin`.

Dans ce fichier nous avons une classe `EntityCrudController` qui va hériter de la classe `AbstractCrudController` qui va elle-même hériter de la classe `AbstractEasyAdminController`.

**getEntityFqcn()**  C'est une méthode qui va nous permettre de dire à easyadmin quelle entité nous voulons afficher.

**configureFields(string $pageName): iterable**  C'est une méthode qui va nous permettre de configurer les champs que nous voulons afficher.Nous avons plusieurs types de champs que nous pouvons afficher.

- `TextField::new('nom')` : permet d'afficher un champ de type text.
- `TextEditorField::new('nom')` : permet d'afficher un champ de type textarea.
- `BooleanField::new('nom')` : permet d'afficher un champ de type checkbox.
- `DateField::new('nom')` : permet d'afficher un champ de type date.
- `DateTimeField::new('nom')` : permet d'afficher un champ de type datetime.
- `EmailField::new('nom')` : permet d'afficher un champ de type email.
- `IntegerField::new('nom')` : permet d'afficher un champ de type number.
- `MoneyField::new('nom')` : permet d'afficher un champ de type number avec un symbole de devise.
- `SlugField::new('nom')` : permet d'afficher un champ de type text.
- `ImageField::new('nom')` : permet d'afficher un champ de type file.Pour les images il faut mettre `->setBasePath('chemin vers le dossier public')` pour afficher l'image, `->setUploadDir('chemin vers le dossier public')` pour uploader l'image et `->setUploadedFileNamePattern('[randomhash].[extension]')` pour renommer l'image.