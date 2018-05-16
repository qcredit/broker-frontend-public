<?php

/* index.twig */
class __TwigTemplate_ee531646b84b3ca9e4d995645d850b724cf8f380a4bdf909920df5acfd4ed3ba extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.twig", "index.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'hero' => array($this, 'block_hero'),
            'container' => array($this, 'block_container'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "qCredit";
    }

    // line 3
    public function block_hero($context, array $blocks = array())
    {
        // line 4
        echo "<section class=\"row home-hero\">
  <div class=\"col-sm-12 col-md-5 home-hero-half home-hero-left\">
    <h2>Szybka gotówka, którą możesz spłacić swoje wszystkie długi</h2>
    <p>Chciałbyś mieć dodatkowe 100 PLN lub nawet 50 000 PLN w kieszeni? QCredit pomoże Ci zdobyć te środki. I to bez wychodzenia z domu. Ponad 30 autoryzowanych pożyczkodawców w QCredit czeka na Ciebie ze swoja ofertą.</p>
  </div>
  <div class=\"col-sm-12 col-md-7 home-hero-half home-hero-right\">
    <form class=\"landing-form\" method=\"post\" action=\"/application\">
      <div class=\"landing-form-upper\">
        <!-- Landing form for laptop/desktop devices -->
        ";
        // line 13
        $this->loadTemplate("\$sliders.twig", "index.twig", 13)->display($context);
        // line 14
        echo "      </div>
      <div class=\"landing-form-lower\">
        <div class=\"landing-inputs form-fields\">
          <div class=\"field text firstName\">
            <label for=\"\">";
        // line 18
        echo gettext("Insert your first name");
        echo "</label>
            <input type=\"text\" name=\"firstName\" id=\"firstName\">
          </div>
          <div class=\"field text email\">
            <label for=\"\">";
        // line 22
        echo gettext("Insert your email address");
        echo "</label>
            <input type=\"email\" name=\"email\" id=\"email\">
          </div>
        </div>
        <div class=\"landing-form-lower-footer\">
          <p class=\"landing-terms\">";
        // line 27
        echo gettext("*Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. ");
        echo "</p>
          <button class=\"btn broker-btn broker-btn-gold\">";
        // line 28
        echo gettext("Apply for a loan");
        echo "</button>
        </div>
      </div>
      <input type=\"hidden\" name=\"";
        // line 31
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "name", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "name", array()), "html", null, true);
        echo "\">
      <input type=\"hidden\" name=\"";
        // line 32
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "value", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "value", array()), "html", null, true);
        echo "\">
    </form>
</section>
<section class=\"row home-partners\"></section>
";
    }

    // line 37
    public function block_container($context, array $blocks = array())
    {
        // line 38
        echo "  <section class=\"row home-how\">
    <h1 class=\"col-sm-12 text-center\">Jak to działa?</h1>
    <div class=\"col-sm-12 col-lg-4 circle-block\">
      <div class=\"circle-block-icon\"><img src=\"src/images/icons/pen.svg\" alt=\"Fill the form\"></div>
      <div class=\"circle-block-text\">
        <p class=\"circle-block-heading\"><strong>Pożyczki online</strong></p>
        <p class=\"circle-block-description\">Wniosek o pożyczkę złożysz bez żadnego wysiłku, szybko i bezpiecznie dla Ciebie. Możesz go wypełnić w wygodnym dla Ciebie miejscu, z zachowaniem prywatności i poufności jakiej wymagasz.</p>
      </div>
    </div>
    <div class=\"col-sm-12 col-lg-4 circle-block\">
      <div class=\"circle-block-icon\"><img src=\"src/images/icons/form.svg\" alt=\"Choose the best offer\"></div>
      <div class=\"circle-block-text\">
        <p class=\"circle-block-heading\"><strong>QCredit to pożyczka na dowolny cel</strong></p>
        <p class=\"circle-block-description\">Tylko jeden wniosek i możesz zrealizować swój dowolny cel za gotówkę od rekomendowanego, wiarygodnego pożyczkodawcy.</p>
      </div>
    </div>
    <div class=\"col-sm-12 col-lg-4 circle-block\">
      <div class=\"circle-block-icon\"><img src=\"src/images/icons/money.svg\" alt=\"Get the money\"></div>
      <div class=\"circle-block-text\">
        <p class=\"circle-block-heading\"><strong>Pieniądze od razu na Twoim koncie</strong></p>
        <p class=\"circle-block-description\">Kolejną zaletą QCredit jest szybki przelew gotówki bezpośrednio na Twój rachunek bankowy nawet tego samego dnia!</p>
      </div>
    </div>
  </section>
  <section class=\"row home-about\">
    <h1 class=\"col-sm-12 text-center\">Kilka słów o pożyczkach</h1>
    <div class=\"col-sm-12 col-lg-6 home-about-half home-about-left\">
      <p><strong>Czy to naprawdę jest bezpłatne?</strong></p>
      <p>Nigdy nie będziesz płacić za usługi QCredit. Jedyne koszty pożyczki wynikają z oferty jaką złoży Tobie pożyczkodawca. Pamiętaj, aby zawsze przeczytać umowę przed jej podpisaniem.</p>
      <p><strong>Co się stanie jeśli nie zapłacę raty na czas?</strong></p>
      <p>Pożyczkodawcy zazwyczaj oferują możliwość przesunięcia daty płatności, jednakże zalecamy kontakt bezpośrednio z pożyczkodawcą. Pamiętaj, że niektórzy pobierają dodatkową opłatę za przesunięcie płatności. Jeśli masz pytania dotyczące dat płatności lub wysokości rat – dzwoń bezpośrednio do swojego pożyczkodawcy.</p>
      <p><strong>Kiedy mam spłacić pożyczkę?</strong></p>
      <p>Musisz sprawdzić swoją umowę pożyczki, gdyż mogą się one różnić w zależności od pożyczkodawcy.</p>
    </div>
    <div class=\"col-sm-12 col-lg-6 home-about-half home-about-right image-container about-image-container-1\">
      <img srcset=\"src/images/img_comp_1_sm.jpg 450w,
             src/images/img_comp_1.jpg 792w\"
     sizes=\"(max-width: 420px) 400px,
            792px\"
     src=\"src/images/img_comp_1.jpg\" alt=\"Qcredit image compilation\">
    </div>
    <div class=\"col-sm-12 col-lg-6 home-about-half home-about-right image-container about-image-container-2\">
      <img srcset=\"src/images/img_comp_2_sm.jpg 450w,
             src/images/img_comp_2.jpg 773w\"
     sizes=\"(max-width: 420px) 400px,
            773px\"
     src=\"src/images/img_comp_2.jpg\" alt=\"Qcredit image compilation\">
    </div>
    <div class=\"col-sm-12 col-lg-6 home-about-half home-about-left\">
      <p><strong>Ważne informacje dla klienta</strong></p>
      <p>Pożyczki ratalne różnią się od innych pożyczek, jak na przykład od kredytu hipotecznego albo pożyczki na zakup samochodu. Każda z pożyczek zapewni Ci środki finansowe, jednakże tylko pieniądze z pożyczek ratalnych mogą zostać przeznaczone na dowolny, wybrany przez Ciebie, cel. Możesz zapłacić za opiekę medyczną, sfinansować remont mieszkania, naprawić samochód , wyjechać na wczasy lub spełnić dowolną inną swoją potrzebę. Pożyczkę zwracasz w wyznaczonym, ustalonym w umowie, terminie bezpośrednio do pożyczkodawcy.</p>
      <p><strong>Na co mogę wydać pieniądze z pożyczki?</strong></p>
      <p>Pożyczki mogą pomóc Ci w remoncie mieszkania, pokryciu nieoczekiwanych wydatków, dodatkowych zakupach, lub wyjeździe na wczasy. Środki od pożyczkodawcy możesz przeznaczyć na dowolny wybrany przez siebie cel.  Wystarczy że wypełnisz prosty wniosek online i jeśli będzie on zaakceptowany to otrzymasz szybko pieniądze!</p>
      <p><strong>Co to jest RRSO?</strong></p>
      <p>Rzeczywista Roczna Stopa Oprocentowania to procentowy całkowity koszt pożyczki w ujęciu rocznym. QCredit nie jest pożyczkodawcą i nie może przedstawić informacji o RRSO, opłatach, oprocentowaniu oferty jaką możesz otrzymać. RRSO może się różnić w zależności od oferty jaką wybierzesz i w zależności od pożyczkodawcy. Pamiętaj, że informacje o RRSO powinieneś otrzymać od pożyczkodawcy przed podpisaniem umowy.</p>
    </div>
  </section>
  <section class=\"row justify-content-md-center\">
    <h1 class=\"col-sm-12 text-center small-mar\">W QCredit jesteś w dobrych rękach</h1>
    <p class=\"col-sm-12 col-md-8 text-center\">Każdego dnia nasz zespół dostarcza profesjonalne rozwiązania finansowe dla Klientów. Jesteśmy w stałym kontakcie z wieloma pożyczkodawcami, którzy mogą Ci zaoferować nawet do 50 000 PLN z natychmiastową decyzją.</p>
    <p class=\"col-sm-12 col-md-8 text-center\"><a href=\"/application\" class=\"btn broker-btn broker-btn-gold\">";
        // line 98
        echo gettext("Apply for a loan");
        echo "</a></p>
  </section>
";
    }

    public function getTemplateName()
    {
        return "index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  169 => 98,  107 => 38,  104 => 37,  93 => 32,  87 => 31,  81 => 28,  77 => 27,  69 => 22,  62 => 18,  56 => 14,  54 => 13,  43 => 4,  40 => 3,  34 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "index.twig", "/app/templates/index.twig");
    }
}
