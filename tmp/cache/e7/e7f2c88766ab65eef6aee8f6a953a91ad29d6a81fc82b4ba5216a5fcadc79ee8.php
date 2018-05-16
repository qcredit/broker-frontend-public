<?php

/* admin/partner-form.twig */
class __TwigTemplate_4cc1275ee1c9f67225c4eb781c1ba8f8c59de10736c0268449f449a2ac23ce13 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/partner-form.twig", 1);
        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'container' => array($this, 'block_container'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_title($context, array $blocks = array())
    {
        echo "Update info for ";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "name", array()), "html", null, true);
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "    <form method=\"POST\">
        <div class=\"row\">
            <div class=\"col-sm-6 mx-auto\">
                <div class=\"form-group\">
                    <input type=\"text\" name=\"name\" placeholder=\"Name\" class=\"form-control\" value=\"";
        // line 8
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "name", array()), "html", null, true);
        echo "\"/>
                    ";
        // line 9
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "name", array())) {
            // line 10
            echo "                        <small class=\"form-text text-danger\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "name", array()), "html", null, true);
            echo "</small>
                    ";
        }
        // line 12
        echo "                </div>
                <div class=\"form-group\">
                    <input type=\"text\" name=\"identifier\" placeholder=\"Identifier\" class=\"form-control\" value=\"";
        // line 14
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "identifier", array()), "html", null, true);
        echo "\"/>
                    ";
        // line 15
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "identifier", array())) {
            // line 16
            echo "                        <small class=\"form-text text-danger\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "identifier", array()), "html", null, true);
            echo "</small>
                    ";
        }
        // line 18
        echo "                </div>
                <div class=\"form-group\">
                    <select name=\"status\">
                        <option value=\"0\" ";
        // line 21
        if ( !twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "status", array())) {
            echo " selected ";
        }
        echo ">Disabled</option>
                        <option value=\"1\" ";
        // line 22
        if (twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "status", array())) {
            echo " selected ";
        }
        echo ">Enabled</option>
                    </select>
                    ";
        // line 24
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "status", array())) {
            // line 25
            echo "                        <small class=\"form-text text-danger\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "status", array()), "html", null, true);
            echo "</small>
                    ";
        }
        // line 27
        echo "                </div>
                <div class=\"form-group\">
                    <input type=\"integer\" name=\"commission\" placeholder=\"Commission\" class=\"form-control\" value=\"";
        // line 29
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "commission", array()), "html", null, true);
        echo "\"/>
                    ";
        // line 30
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "commission", array())) {
            // line 31
            echo "                        <small class=\"form-text text-danger\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "commission", array()), "html", null, true);
            echo "</small>
                    ";
        }
        // line 33
        echo "                </div>
            </div>
        </div>
        <div class=\"row\">
            <div class=\"col-sm-6 mx-auto\">
                <button type=\"submit\" class=\"btn broker-btn broker-btn-blue\">Submit</button>
            </div>
        </div>
        <input type=\"hidden\" name=\"";
        // line 41
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "name", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "name", array()), "html", null, true);
        echo "\">
        <input type=\"hidden\" name=\"";
        // line 42
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "value", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "value", array()), "html", null, true);
        echo "\">
    </form>
";
    }

    public function getTemplateName()
    {
        return "admin/partner-form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  135 => 42,  129 => 41,  119 => 33,  113 => 31,  111 => 30,  107 => 29,  103 => 27,  97 => 25,  95 => 24,  88 => 22,  82 => 21,  77 => 18,  71 => 16,  69 => 15,  65 => 14,  61 => 12,  55 => 10,  53 => 9,  49 => 8,  43 => 4,  40 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/partner-form.twig", "/app/templates/admin/partner-form.twig");
    }
}
