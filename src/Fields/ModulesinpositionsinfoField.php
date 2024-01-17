<?php
/**
 * @package       WT Add products info to Joomla script options
 * @version       2.0.1
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @copyright     Copyright (C) 2023 Sergey Tolkachyov
 * @license       GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Joomla\Plugin\System\Wt_modules_in_jshopping_positions\Fields;

defined('_JEXEC') or die;

use Joomla\CMS\Form\Field\NoteField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

class ModulesinpositionsinfoField extends NoteField
{

    protected $type = 'Modulesinpositionsinfo';

    /**
     * Method to get the field input markup for a spacer.
     * The spacer does not have accept input.
     *
     * @return  string  The field input markup.
     *
     * @since   1.7.0
     */
    protected function getInput(): string
    {
        return ' ';
    }

    /**
     * @return  string  The field label markup.
     *
     * @since   1.7.0
     */
    protected function getLabel(): string
    {
        $data            = $this->form->getData();
        $module_position = $data->get('position');
        $class = [];

        if (!empty($this->class))
        {
            $class[] = $this->class;
        }
        $class       = $class ? ' class="' . implode(' ', $class) . '"' : '';

        if (empty($module_position) || $module_position == 'none')
        {
            $html= Text::_('PLG_WT_MODULES_IN_JSHOPPING_MODULESINPOSITIONSINFO_NOT_SELECTED');
            return '</div><div '.$class.'>' . $html;
        }

        $db    = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select([$db->quoteName('title'), $db->quoteName('id'), $db->quoteName('published')])
            ->from($db->quoteName('#__modules'))
            ->where('position = ' . $db->quote($module_position));

        $db->setQuery($query);
        $modules = $db->loadAssocList();

        $title       = $this->element['label'] ? (string) $this->element['label'] : ($this->element['title'] ? (string) $this->element['title'] : '');
        $description = (string) $this->element['description'];
        $title       = '<h4>' . Text::_($title) . '</h4>';
        $html        = [$title];
        $html[]      = '<table class="table table-sm">';
        $html[]      = '<thead><tr><th>'.Text::_('PLG_WT_MODULES_IN_JSHOPPING_MODULESINPOSITIONSINFO_ID').'</th><th>'.Text::_('PLG_WT_MODULES_IN_JSHOPPING_MODULESINPOSITIONSINFO_TITLE').'</th><th>'.Text::_('PLG_WT_MODULES_IN_JSHOPPING_MODULESINPOSITIONSINFO_STATUS').'</th></tr></thead>';
        $html[]      = '<tbody>';
        foreach ($modules as $module)
        {
            $html[] = '<tr>';
            $html[] = '<td>' . $module['id'] . '</td><td>' . $module['title'] . '</td><td>' . ($module['published'] == 1 ? '<span class="badge badge-success bg-success">' . Text::_('JPUBLISHED') . '</span>' : '<span class="badge badge-important bg-danger">' . Text::_('JUNPUBLISHED') . '</span>') . '</td>';
            $html[] = '</tr>';
        }
        $html[] = '</tbody>';
        $html[] = '</table>';
        $html[] = $description;
        $html   = implode('', $html);

        return '</div><div '.$class.'>' . $html;
    }

    /**
     * Method to get the field title.
     *
     * @return  string  The field title.
     *
     * @since   1.7.0
     */
    protected function getTitle(): string
    {
        return $this->getLabel();
    }
}
?>