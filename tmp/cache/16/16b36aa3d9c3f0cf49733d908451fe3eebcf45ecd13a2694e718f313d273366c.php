<?php

/* admin/user-form.twig */
class __TwigTemplate_3cdda8656f531c1bd7f14600a2b592e4002e8f90262aaddcec91fc3c4d2c4075 extends Twig_Template
{
    private $source;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "admin/user-form.twig", 1);
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
        if (twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "email", array())) {
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "email", array()), "html", null, true);
        } else {
            echo "New user";
        }
    }

    // line 3
    public function block_container($context, array $blocks = array())
    {
        // line 4
        echo "<form method=\"POST\">
    <div class=\"row\">
        <div class=\"col-sm-6 mx-auto\">
            <div class=\"form-group\">
                <input type=\"text\" name=\"email\" placeholder=\"Email\" class=\"form-control\" value=\"";
        // line 8
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "email", array()), "html", null, true);
        echo "\"/>
                ";
        // line 9
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "errors", array()), "email", array())) {
            // line 10
            echo "                    <small class=\"form-text text-danger\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["user"] ?? null), "errors", array()), "email", array()), "html", null, true);
            echo "</small>
                ";
        }
        // line 12
        echo "            </div>
            <div class=\"form-group\">
                <select name=\"accessLevel\">
                    ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["accessLevelList"] ?? null));
        foreach ($context['_seq'] as $context["val"] => $context["level"]) {
            // line 16
            echo "                      <option value=\"";
            echo twig_escape_filter($this->env, $context["val"], "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $context["level"], "html", null, true);
            echo "</option>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['val'], $context['level'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 18
        echo "                </select>
                ";
        // line 19
        if (twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "status", array())) {
            // line 20
            echo "                    <small class=\"form-text text-danger\">";
            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["partner"] ?? null), "errors", array()), "status", array()), "html", null, true);
            echo "</small>
                ";
        }
        // line 22
        echo "            </div>
        </div>
    </div>
    <div class=\"row\">
        <div class=\"col-sm-6 mx-auto\">
            <button type=\"submit\" class=\"btn broker-btn broker-btn-blue\">Add</button>
        </div>
    </div>
    <input type=\"hidden\" name=\"";
        // line 30
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "name", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "name", array()), "html", null, true);
        echo "\">
    <input type=\"hidden\" name=\"";
        // line 31
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "keys", array()), "value", array()), "html", null, true);
        echo "\" value=\"";
        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, ($context["csrf"] ?? null), "value", array()), "html", null, true);
        echo "\">
</form>
";
    }

    public function getTemplateName()
    {
        return "admin/user-form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  111 => 31,  105 => 30,  95 => 22,  89 => 20,  87 => 19,  84 => 18,  73 => 16,  69 => 15,  64 => 12,  58 => 10,  56 => 9,  52 => 8,  46 => 4,  43 => 3,  33 => 2,  15 => 1,);
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "admin/user-form.twig", "/app/templates/admin/user-form.twig");
    }
}
