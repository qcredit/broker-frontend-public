<?php

/* contact.twig */
class __TwigTemplate_930a7379cc2d36dec934b0f456a195c37e3f7e11372a7ad9c9b26e390064212c extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.twig", "contact.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
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
        echo "Contact";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "<div class=\"row justify-content-lg-center\">
  <section class=\"col-sm-12 text-center\">
    <h1>Contact</h1>
  </section>
  <div class=\"col-sm-12 col-md-6 col-lg-4 contact-page-blocks\">
    <div class=\"row\">
      <div class=\"col-sm-12 col-lg-9 circle-block\">
        <a href=\"mailto:broker@broker.pl\">
          <div class=\"circle-block-icon\"><img src=\"src/images/ic_mail.svg\" alt=\"email\"></div>
        </a>
        <div class=\"circle-block-text\">
          <span class=\"circle-block-step text-uppercase\">&nbsp;</span>
          <p class=\"circle-block-heading\"><strong>E-mail</strong></p>
          <p class=\"circle-block-description\"><a href=\"mailto:broker@broker.pl\">broker@broker.pl</a></p>
        </div>
      </div>
      <div class=\"col-sm-12 col-lg-9 circle-block\">
        <a href=\"tel:0564783131\">
          <div class=\"circle-block-icon\"><img src=\"src/images/ic_phone.svg\" alt=\"Phone\"></div>
        </a>
        <div class=\"circle-block-text\">
          <span class=\"circle-block-step text-uppercase\">&nbsp;</span>
          <p class=\"circle-block-heading\"><strong>Phone</strong></p>
          <p class=\"circle-block-description\"><a href=\"tel:0564783131\">0564783131</a></p>
        </div>
      </div>
      <div class=\"col-sm-12 col-lg-9 circle-block\">
        <div class=\"circle-block-icon\"><img src=\"src/images/ic_location.svg\" alt=\"Location\"></div>
        <div class=\"circle-block-text\">
          <span class=\"circle-block-step text-uppercase\">&nbsp;</span>
          <p class=\"circle-block-heading\"><strong>Address</strong></p>
          <p class=\"circle-block-description\">Lorem ipsum 28-2, Tallinn, 15554 Estonia</p>
        </div>
      </div>
    </div>
  </div>
  <div class=\"col-sm-12 col-md-6\">
    <form action=\"\" class=\"contact-form\">
      <div class=\"field text name\">
        <label for=\"\">Insert your name*</label>
          <input type=\"text\" name=\"name\" id=\"name\"/>
      </div>
      <div class=\"field text emailaddress\">
        <label for=\"\">Insert your email*</label>
          <input type=\"text\" name=\"emailaddress\" id=\"emailaddress\"/>
      </div>
      <div class=\"field textarea email\">
        <label for=\"\">Insert your message*</label>
        <textarea name=\"message\" id=\"message\"></textarea>
      </div>
      <div class=\"field submit\">
        <button class=\"btn broker-btn broker-btn-gold\">Send</button>
      </div>
    </form>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "contact.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "contact.twig", "/app/templates/contact.twig");
    }
}
