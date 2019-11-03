# Gestion des formulaires


ProductController.php : 

```
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
...

/**
 * @Route("/new", name="product_new", methods={"GET","POST"})
 */
public function new(Request $request): Response
{
    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $product->setUser($this->getUser());
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_index');
    }

    return $this->render('product/new.html.twig', [
        'product' => $product,
        'form' => $form->createView(),
    ]);
}
```

src/Form/ProductType.php : 

```
<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => '250 caractères maximum'
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('description', TextareaType::class,
                [

            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
            ])
            ->add('price')
            ->add('category', EntityType::class,
                array(
                    'class' => 'App:Category',
                    'choice_label' => 'name',
                    'query_builder' => function (CategoryRepository $cr) use ( $options )  {
                        return $cr->createQueryBuilder('a')
                            ->orderBy('a.name', 'ASC');
                    }
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

```

Lien vers la documentation : 

> https://symfony.com/doc/current/reference/constraints.html
> https://symfony.com/doc/current/forms.html

templates/product/_form.html.twig :

```
{{ form_start(form, {'attr': {'class' : 'form'}}) }}
<div class="form-row">
    <div class="form-group col-md-6">
        {{ form_label(form.name) }}
        {{ form_widget(form.name, {'attr':{'class' : 'form-control', 'placeholder' : 'Nom du produit'}}) }}
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-12">
        {{ form_label(form.description) }}
        {{ form_widget(form.description, {'attr':{'class' : 'form-control', 'placeholder' : 'Votre prénom'}}) }}
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        {{ form_label(form.image) }}
        {{ form_widget(form.image, {'attr':{'class' : 'form-control'}}) }}
    </div>
    <div class="form-group col-md-6">
        {{ form_label(form.price) }}
        {{ form_widget(form.price, {'attr':{'class' : 'form-control'}}) }}
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-6">
        {{ form_label(form.category) }}
        {{ form_widget(form.category, {'attr':{'class' : 'form-control'}}) }}
    </div>
</div>
<button class="btn btn-primary">{{ button_label|default('Sauvegarder') }}</button>
{{ form_end(form) }}
```

