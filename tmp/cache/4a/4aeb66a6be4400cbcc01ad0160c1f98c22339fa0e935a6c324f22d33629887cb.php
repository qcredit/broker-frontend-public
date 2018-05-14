<?php

/* application/thankyou.twig */
class __TwigTemplate_4e89e20dcc830a93c0e0d9648e55ac7c76324e25b925875017df8c768e5cbed7 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.twig", "application/thankyou.twig", 1);
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
        echo "Thank you";
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "  <div class=\"row justify-content-sm-center thank-you-row\">
    <section class=\"col-sm-8 text-center\">
      <h1>A great big thank you!</h1>
    </section>
    <div class=\"col-sm-12 col-md-10 col-lg-7 text-center\">
      <p>Welcome to the Broker family! We've forwarded your application to loan providers and sent a confirmation email to <strong>";
        // line 9
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["application"] ?? null), "email", array()), "html", null, true);
        echo "</strong>. You can expect them to contact you within 3 days.</p>
    </div>
  </div>
";
    }

    public function getTemplateName()
    {
        return "application/thankyou.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 9,  42 => 4,  39 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "application/thankyou.twig", "/app/templates/application/thankyou.twig");
    }
}
