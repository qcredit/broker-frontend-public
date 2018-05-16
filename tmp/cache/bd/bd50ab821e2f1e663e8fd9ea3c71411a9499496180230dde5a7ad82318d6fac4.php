<?php

/* mail/offer-confirmation.twig */
class __TwigTemplate_3122b829b45e0c61f1434709dd3d10705a548aca464378a8f9a370afa0af5584 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("mail/base-html.twig", "mail/offer-confirmation.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "mail/base-html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "  <p style=\"margin:0 10px 0 0;Margin:0 10px 0 0;margin-bottom:25px;Margin-bottom:25px;padding:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1.3;font-weight:400;text-align:left;\">
    Hi, ";
        // line 7
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "application", array()), "fullName", array()), "html", null, true);
        echo "
  </p>

  <p style=\"margin:0 10px 0 0;Margin:0 10px 0 0;margin-bottom:25px;Margin-bottom:25px;padding:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1.3;font-weight:400;text-align:left;\">
    Thank you for choosing qCredit! Thank you for choosing the offer with following details:
  </p>
  <p style=\"margin:0 10px 0 0;Margin:0 10px 0 0;margin-bottom:25px;Margin-bottom:25px;padding:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1.3;font-weight:400;text-align:left;\">
    &bull; <b>Amount:</b> ";
        // line 14
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "loanAmount", array()), "html", null, true);
        echo " <br>
    &bull; <b>Term:</b> ";
        // line 15
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "loanTerm", array()), "html", null, true);
        echo " <br>
    &bull; <b>Interest:</b> ";
        // line 16
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "interest", array()), "html", null, true);
        echo " <br>
  </p>
  <p style=\"margin:0 10px 0 0;Margin:0 10px 0 0;margin-bottom:25px;Margin-bottom:25px;padding:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;line-height:1.3;font-weight:400;text-align:left;\">
  ";
        // line 19
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["offer"] ?? null), "partner", array()), "name", array()), "html", null, true);
        echo " will contact you regarding the signing process of the contract.
</p>
";
    }

    public function getTemplateName()
    {
        return "mail/offer-confirmation.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  69 => 19,  63 => 16,  59 => 15,  55 => 14,  45 => 7,  42 => 6,  39 => 5,  33 => 3,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "mail/offer-confirmation.twig", "/app/templates/mail/offer-confirmation.twig");
    }
}
