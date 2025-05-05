<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* database/structure/show_create.twig */
class __TwigTemplate_447fe64feb0d688b74575daa6b013ae7521a64d74deff7cca58723e8fff37827 extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<div class=\"show_create_results\">
  <h2>";
echo _gettext("Showing create queries");
        // line 2
        echo "</h2>

  ";
        // line 4
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, ($context["tables"] ?? null), "tables", [], "any", false, false, false, 4))) {
            // line 5
            echo "    <fieldset class=\"pma-fieldset\">
      <legend>";
echo _gettext("Tables");
            // line 6
            echo "</legend>
      <table class=\"table table-striped show_create\">
        <thead>
          <tr>
            <th>";
echo _gettext("Table");
            // line 10
            echo "</th>
            <th>";
echo _gettext("Create table");
            // line 11
            echo "</th>
          </tr>
        </thead>
        <tbody>
          ";
            // line 15
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["tables"] ?? null), "tables", [], "any", false, false, false, 15));
            foreach ($context['_seq'] as $context["_key"] => $context["table"]) {
                // line 16
                echo "            <tr>
              <td><strong>";
                // line 17
                echo twig_get_attribute($this->env, $this->source, $context["table"], "name", [], "any", false, false, false, 17);
                echo "</strong></td>
              <td>";
                // line 18
                echo twig_get_attribute($this->env, $this->source, $context["table"], "show_create", [], "any", false, false, false, 18);
                echo "</td>
            </tr>
          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['table'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 21
            echo "        </tbody>
      </table>
    </fieldset>
  ";
        }
        // line 25
        echo "
  ";
        // line 26
        if ( !twig_test_empty(twig_get_attribute($this->env, $this->source, ($context["tables"] ?? null), "views", [], "any", false, false, false, 26))) {
            // line 27
            echo "    <fieldset class=\"pma-fieldset\">
      <legend>";
echo _gettext("Views");
            // line 28
            echo "</legend>
      <table class=\"table table-striped show_create\">
        <thead>
          <tr>
            <th>";
echo _gettext("View");
            // line 32
            echo "</th>
            <th>";
echo _gettext("Create view");
            // line 33
            echo "</th>
          </tr>
        </thead>
        <tbody>
          ";
            // line 37
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, ($context["tables"] ?? null), "views", [], "any", false, false, false, 37));
            foreach ($context['_seq'] as $context["_key"] => $context["view"]) {
                // line 38
                echo "            <tr>
              <td><strong>";
                // line 39
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["view"], "name", [], "any", false, false, false, 39), "html", null, true);
                echo "</strong></td>
              <td>";
                // line 40
                echo twig_get_attribute($this->env, $this->source, $context["view"], "show_create", [], "any", false, false, false, 40);
                echo "</td>
            </tr>
          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['view'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 43
            echo "        </tbody>
      </table>
    </fieldset>
  ";
        }
        // line 47
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "database/structure/show_create.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  146 => 47,  140 => 43,  131 => 40,  127 => 39,  124 => 38,  120 => 37,  114 => 33,  110 => 32,  103 => 28,  99 => 27,  97 => 26,  94 => 25,  88 => 21,  79 => 18,  75 => 17,  72 => 16,  68 => 15,  62 => 11,  58 => 10,  51 => 6,  47 => 5,  45 => 4,  41 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/structure/show_create.twig", "C:\\OSPanel\\home\\phpmyadmin\\public\\templates\\database\\structure\\show_create.twig");
    }
}
