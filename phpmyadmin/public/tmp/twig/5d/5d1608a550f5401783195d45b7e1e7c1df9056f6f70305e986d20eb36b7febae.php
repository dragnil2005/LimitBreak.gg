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

/* database/privileges/index.twig */
class __TwigTemplate_57430d1838c3ae99b1f5a410bc50d3d2e9623c9d61182f8bb21a739c529a480d extends Template
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
        if (($context["is_superuser"] ?? null)) {
            // line 2
            echo "  <form id=\"usersForm\" action=\"";
            echo PhpMyAdmin\Url::getFromRoute("/server/privileges");
            echo "\">
    ";
            // line 3
            echo PhpMyAdmin\Url::getHiddenInputs(($context["db"] ?? null));
            echo "

    <div class=\"w-100\">
      <fieldset class=\"pma-fieldset\">
        <legend>
          ";
            // line 8
            echo PhpMyAdmin\Html\Generator::getIcon("b_usrcheck");
            echo "
          ";
            // line 9
            echo twig_sprintf(_gettext("Users having access to \"%s\""), ((((("<a href=\"" . ($context["database_url"] ?? null)) . PhpMyAdmin\Url::getCommon(["db" => ($context["db"] ?? null)], "&")) . "\">") . twig_escape_filter($this->env, ($context["db"] ?? null), "html")) . "</a>"));
            echo "
        </legend>

        <div class=\"table-responsive jsresponsive\">
          <table class=\"table table-striped table-hover w-auto\">
            <thead>
              <tr>
                <th></th>
                <th scope=\"col\">";
echo _gettext("User name");
            // line 17
            echo "</th>
                <th scope=\"col\">";
echo _gettext("Host name");
            // line 18
            echo "</th>
                <th scope=\"col\">";
echo _gettext("Type");
            // line 19
            echo "</th>
                <th scope=\"col\">";
echo _gettext("Privileges");
            // line 20
            echo "</th>
                <th scope=\"col\">";
echo _gettext("Grant");
            // line 21
            echo "</th>
                <th scope=\"col\" colspan=\"2\">";
echo _gettext("Action");
            // line 22
            echo "</th>
              </tr>
            </thead>

            <tbody>
              ";
            // line 27
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["privileges"] ?? null));
            $context['_iterated'] = false;
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["privilege"]) {
                // line 28
                echo "                ";
                $context["privileges_amount"] = twig_length_filter($this->env, twig_get_attribute($this->env, $this->source, $context["privilege"], "privileges", [], "any", false, false, false, 28));
                // line 29
                echo "                <tr>
                  <td";
                // line 30
                if ((1 === twig_compare(($context["privileges_amount"] ?? null), 1))) {
                    echo " class=\"align-middle\" rowspan=\"";
                    echo twig_escape_filter($this->env, ($context["privileges_amount"] ?? null), "html", null, true);
                    echo "\"";
                }
                echo ">
                    <input type=\"checkbox\" class=\"checkall\" name=\"selected_usr[]\" id=\"checkbox_sel_users_";
                // line 31
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, false, 31), "html", null, true);
                echo "\" value=\"";
                // line 32
                echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, $context["privilege"], "user", [], "any", false, false, false, 32) . "&amp;#27;") . twig_get_attribute($this->env, $this->source, $context["privilege"], "host", [], "any", false, false, false, 32)), "html", null, true);
                echo "\">
                  </td>
                  <td";
                // line 34
                if ((1 === twig_compare(($context["privileges_amount"] ?? null), 1))) {
                    echo " class=\"align-middle\" rowspan=\"";
                    echo twig_escape_filter($this->env, ($context["privileges_amount"] ?? null), "html", null, true);
                    echo "\"";
                }
                echo ">
                    ";
                // line 35
                if (twig_test_empty(twig_get_attribute($this->env, $this->source, $context["privilege"], "user", [], "any", false, false, false, 35))) {
                    // line 36
                    echo "                      <span class=\"text-danger\">";
echo _gettext("Any");
                    echo "</span>
                    ";
                } else {
                    // line 38
                    echo "                      ";
                    echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["privilege"], "user", [], "any", false, false, false, 38), "html", null, true);
                    echo "
                    ";
                }
                // line 40
                echo "                  </td>
                  <td";
                // line 41
                if ((1 === twig_compare(($context["privileges_amount"] ?? null), 1))) {
                    echo " class=\"align-middle\" rowspan=\"";
                    echo twig_escape_filter($this->env, ($context["privileges_amount"] ?? null), "html", null, true);
                    echo "\"";
                }
                echo ">
                    ";
                // line 42
                echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["privilege"], "host", [], "any", false, false, false, 42), "html", null, true);
                echo "
                  </td>
                  ";
                // line 44
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_get_attribute($this->env, $this->source, $context["privilege"], "privileges", [], "any", false, false, false, 44));
                foreach ($context['_seq'] as $context["_key"] => $context["priv"]) {
                    // line 45
                    echo "                    <td>
                      ";
                    // line 46
                    if ((0 === twig_compare(twig_get_attribute($this->env, $this->source, $context["priv"], "type", [], "any", false, false, false, 46), "g"))) {
                        // line 47
                        echo "                        ";
echo _gettext("global");
                        // line 48
                        echo "                      ";
                    } elseif ((0 === twig_compare(twig_get_attribute($this->env, $this->source, $context["priv"], "type", [], "any", false, false, false, 48), "d"))) {
                        // line 49
                        echo "                        ";
                        if ((0 === twig_compare(twig_get_attribute($this->env, $this->source, $context["priv"], "database", [], "any", false, false, false, 49), twig_replace_filter(($context["db"] ?? null), ["_" => "\\_", "%" => "\\%"])))) {
                            // line 50
                            echo "                          ";
echo _gettext("database-specific");
                            // line 51
                            echo "                        ";
                        } else {
                            // line 52
                            echo "                          ";
echo _gettext("wildcard");
                            echo ": <code>";
                            echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["priv"], "database", [], "any", false, false, false, 52), "html", null, true);
                            echo "</code>
                        ";
                        }
                        // line 54
                        echo "                      ";
                    } elseif ((0 === twig_compare(twig_get_attribute($this->env, $this->source, $context["priv"], "type", [], "any", false, false, false, 54), "r"))) {
                        // line 55
                        echo "                        ";
echo _gettext("routine");
                        // line 56
                        echo "                      ";
                    }
                    // line 57
                    echo "                    </td>
                    <td>
                      <code>
                        ";
                    // line 60
                    if ((0 === twig_compare(twig_get_attribute($this->env, $this->source, $context["priv"], "type", [], "any", false, false, false, 60), "r"))) {
                        // line 61
                        echo "                          ";
                        echo twig_escape_filter($this->env, twig_get_attribute($this->env, $this->source, $context["priv"], "routine", [], "any", false, false, false, 61), "html", null, true);
                        echo "
                          (";
                        // line 62
                        echo twig_escape_filter($this->env, twig_upper_filter($this->env, twig_join_filter(twig_get_attribute($this->env, $this->source, $context["priv"], "privileges", [], "any", false, false, false, 62), ", ")), "html", null, true);
                        echo ")
                        ";
                    } else {
                        // line 64
                        echo "                          ";
                        echo twig_join_filter(twig_get_attribute($this->env, $this->source, $context["priv"], "privileges", [], "any", false, false, false, 64), ", ");
                        echo "
                        ";
                    }
                    // line 66
                    echo "                      </code>
                    </td>
                    <td>
                      ";
                    // line 69
                    echo twig_escape_filter($this->env, ((twig_get_attribute($this->env, $this->source, $context["priv"], "has_grant", [], "any", false, false, false, 69)) ? (_gettext("Yes")) : (_gettext("No"))), "html", null, true);
                    echo "
                    </td>
                    <td>
                      ";
                    // line 72
                    if (($context["is_grantuser"] ?? null)) {
                        // line 73
                        echo "                        <a class=\"edit_user_anchor\" href=\"";
                        echo PhpMyAdmin\Url::getFromRoute("/server/privileges", ["username" => twig_get_attribute($this->env, $this->source,                         // line 74
$context["privilege"], "user", [], "any", false, false, false, 74), "hostname" => twig_get_attribute($this->env, $this->source,                         // line 75
$context["privilege"], "host", [], "any", false, false, false, 75), "dbname" => (((0 !== twig_compare(twig_get_attribute($this->env, $this->source,                         // line 76
$context["priv"], "database", [], "any", false, false, false, 76), "*"))) ? (twig_get_attribute($this->env, $this->source, $context["priv"], "database", [], "any", false, false, false, 76)) : ("")), "tablename" => "", "routinename" => (((twig_get_attribute($this->env, $this->source,                         // line 78
$context["priv"], "routine", [], "any", true, true, false, 78) &&  !(null === twig_get_attribute($this->env, $this->source, $context["priv"], "routine", [], "any", false, false, false, 78)))) ? (twig_get_attribute($this->env, $this->source, $context["priv"], "routine", [], "any", false, false, false, 78)) : (""))]);
                        // line 79
                        echo "\">
                          ";
                        // line 80
                        echo PhpMyAdmin\Html\Generator::getIcon("b_usredit", _gettext("Edit privileges"));
                        echo "
                        </a>
                      ";
                    }
                    // line 83
                    echo "                    </td>
                    <td class=\"text-center\">
                      <a class=\"export_user_anchor ajax\" href=\"";
                    // line 85
                    echo PhpMyAdmin\Url::getFromRoute("/server/privileges", ["username" => twig_get_attribute($this->env, $this->source,                     // line 86
$context["privilege"], "user", [], "any", false, false, false, 86), "hostname" => twig_get_attribute($this->env, $this->source,                     // line 87
$context["privilege"], "host", [], "any", false, false, false, 87), "export" => true, "initial" => ""]);
                    // line 90
                    echo "\">
                        ";
                    // line 91
                    echo PhpMyAdmin\Html\Generator::getIcon("b_tblexport", _gettext("Export"));
                    echo "
                      </a>
                    </td>
                  </tr>
                    ";
                    // line 95
                    if ((1 === twig_compare(($context["privileges_amount"] ?? null), 1))) {
                        // line 96
                        echo "                      <tr class=\"noclick\">
                    ";
                    }
                    // line 98
                    echo "                  ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['priv'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 99
                echo "              ";
                $context['_iterated'] = true;
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['length'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            if (!$context['_iterated']) {
                // line 100
                echo "                <tr>
                  <td colspan=\"7\">
                    ";
echo _gettext("No user found.");
                // line 103
                echo "                  </td>
                </tr>
              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['privilege'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 106
            echo "            </tbody>
          </table>
        </div>

        <div class=\"float-start\">
          <img class=\"selectallarrow\" src=\"";
            // line 111
            echo twig_escape_filter($this->env, $this->extensions['PhpMyAdmin\Twig\AssetExtension']->getImagePath((("arrow_" . ($context["text_dir"] ?? null)) . ".png")), "html", null, true);
            echo "\" alt=\"";
echo _gettext("With selected:");
            // line 112
            echo "\" width=\"38\" height=\"22\">
          <input type=\"checkbox\" id=\"usersForm_checkall\" class=\"checkall_box\" title=\"";
echo _gettext("Check all");
            // line 113
            echo "\">
          <label for=\"usersForm_checkall\">";
echo _gettext("Check all");
            // line 114
            echo "</label>
          <em class=\"with-selected\">";
echo _gettext("With selected:");
            // line 115
            echo "</em>
          <button class=\"btn btn-link mult_submit\" type=\"submit\" name=\"submit_mult\" value=\"export\" title=\"";
echo _gettext("Export");
            // line 116
            echo "\">
            ";
            // line 117
            echo PhpMyAdmin\Html\Generator::getIcon("b_tblexport", _gettext("Export"));
            echo "
          </button>
        </div>
      </fieldset>
    </div>
  </form>
";
        } else {
            // line 124
            echo "  ";
            echo $this->env->getFilter('error')->getCallable()(_gettext("Not enough privilege to view users."));
            echo "
";
        }
        // line 126
        echo "
";
        // line 127
        if (($context["is_createuser"] ?? null)) {
            // line 128
            echo "  <div class=\"row\">
    <div class=\"col-12\">
      <fieldset class=\"pma-fieldset\" id=\"fieldset_add_user\">
        <legend>";
echo _pgettext("Create new user", "New");
            // line 131
            echo "</legend>
        <a id=\"add_user_anchor\" href=\"";
            // line 132
            echo PhpMyAdmin\Url::getFromRoute("/server/privileges", ["adduser" => true, "dbname" =>             // line 134
($context["db"] ?? null)]);
            // line 135
            echo "\" rel=\"";
            echo PhpMyAdmin\Url::getCommon(["checkprivsdb" => ($context["db"] ?? null)]);
            echo "\">
          ";
            // line 136
            echo PhpMyAdmin\Html\Generator::getIcon("b_usradd", _gettext("Add user account"));
            echo "
        </a>
      </fieldset>
    </div>
  </div>
";
        }
    }

    public function getTemplateName()
    {
        return "database/privileges/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  390 => 136,  385 => 135,  383 => 134,  382 => 132,  379 => 131,  373 => 128,  371 => 127,  368 => 126,  362 => 124,  352 => 117,  349 => 116,  345 => 115,  341 => 114,  337 => 113,  333 => 112,  329 => 111,  322 => 106,  314 => 103,  309 => 100,  296 => 99,  290 => 98,  286 => 96,  284 => 95,  277 => 91,  274 => 90,  272 => 87,  271 => 86,  270 => 85,  266 => 83,  260 => 80,  257 => 79,  255 => 78,  254 => 76,  253 => 75,  252 => 74,  250 => 73,  248 => 72,  242 => 69,  237 => 66,  231 => 64,  226 => 62,  221 => 61,  219 => 60,  214 => 57,  211 => 56,  208 => 55,  205 => 54,  197 => 52,  194 => 51,  191 => 50,  188 => 49,  185 => 48,  182 => 47,  180 => 46,  177 => 45,  173 => 44,  168 => 42,  160 => 41,  157 => 40,  151 => 38,  145 => 36,  143 => 35,  135 => 34,  130 => 32,  127 => 31,  119 => 30,  116 => 29,  113 => 28,  95 => 27,  88 => 22,  84 => 21,  80 => 20,  76 => 19,  72 => 18,  68 => 17,  56 => 9,  52 => 8,  44 => 3,  39 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/privileges/index.twig", "C:\\OSPanel\\home\\phpmyadmin\\public\\templates\\database\\privileges\\index.twig");
    }
}
