<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Fields.clickablecontactfields
 * @version     1.0.8
 * @author      Frank Willeke
 * @license     GNU/GPL
 */

// No direct access to this file
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Log\Log;

/**
 * @brief Make contact fields clickable by overriding their rendered value.
 */
class PlgFieldsClickablecontactfields extends CMSPlugin
{
	/**
	 * @brief After a field is prepared, replace its output with clickable HTML.
	 *
	 * @param   string   $context  The display context (e.g. 'com_users.profile')
	 * @param   object   $item     The item (e.g. the user object)
	 * @param   object   $field    The Field definition
	 * @param   mixed    &$value   The field’s rendered value (passed by reference)
	 *
	 * @return  void
	 */
	public function onCustomFieldsAfterPrepareField(string $context, $item, $field, &$value): void
	{
		// No empty values
		if (trim($value) === '')
		{
			return;
		}

		$name           = strtolower($field->name);
		$phoneFields    = array_map('trim', explode(',', strtolower($this->params->get('phone_fields',    ''))));
		$smsFields      = array_map('trim', explode(',', strtolower($this->params->get('sms_fields',    ''))));
		$smsFields      = array_map('trim', explode(',', strtolower($this->params->get('sms_fields',    ''))));
		$waFields       = array_map('trim', explode(',', strtolower($this->params->get('whatsapp_fields', ''))));
		$tgFields       = array_map('trim', explode(',', strtolower($this->params->get('telegram_fields',''))));
		$signalFields   = array_map('trim', explode(',', strtolower($this->params->get('signal_fields',   ''))));
		$viberFields    = array_map('trim', explode(',', strtolower($this->params->get('viber_fields',   ''))));
		$facebookFields = array_map('trim', explode(',', strtolower($this->params->get('facebook_fields',   ''))));
		$sanitizeDigits = fn(string $v): string => preg_replace('/\D+/', '', $v);

		// Phone
		if (\in_array($name, $phoneFields, true))
		{
			Log::add('onCustomFieldsAfterPrepareField: '.$field->name.' = '.$value, Log::DEBUG, 'plg_fields_clickablecontactfields');
			$num   = $sanitizeDigits($value);
			$value = '<a href="tel:'.$num.'">'.htmlspecialchars($value).'</a>';
		}
		// SMS
		elseif (\in_array($name, $smsFields, true))
		{
			Log::add('onCustomFieldsAfterPrepareField: '.$field->name.' = '.$value, Log::DEBUG, 'plg_fields_clickablecontactfields');
			$num   = $sanitizeDigits($value);
			$value = '<a href="sms:'.$num.'">'.htmlspecialchars($value).'</a>';
		}
		// WhatsApp
		elseif (\in_array($name, $waFields, true))
		{
			Log::add('onCustomFieldsAfterPrepareField: '.$field->name.' = '.$value, Log::DEBUG, 'plg_fields_clickablecontactfields');
			$num   = $sanitizeDigits($value);
			$value = '<a href="https://wa.me/'.$num.'" target="_blank" rel="noopener">'.htmlspecialchars($value).'</a>';
		}
		// Telegram
		elseif (\in_array($name, $tgFields, true))
		{
			Log::add('onCustomFieldsAfterPrepareField: '.$field->name.' = '.$value, Log::DEBUG, 'plg_fields_clickablecontactfields');
			$p     = trim($value);
			$link  = preg_match('/^\+?\d+$/', $p)
				   ? 'https://t.me/+'.$sanitizeDigits($p)
				   : 'https://t.me/'.urlencode(ltrim($p, '@'));
			$value = '<a href="'.$link.'" target="_blank" rel="noopener">'.htmlspecialchars($p).'</a>';
		}
		// Signal
		elseif (\in_array($name, $signalFields, true))
		{
			Log::add('onCustomFieldsAfterPrepareField: '.$field->name.' = '.$value, Log::DEBUG, 'plg_fields_clickablecontactfields');
			$num   = $sanitizeDigits($value);
			$link  = 'https://signal.me/#p/+' . $num;
			$value = '<a href="'.$link.'" target="_blank" rel="noopener">'.htmlspecialchars($value).'</a>';
		}
		// Viber
		elseif (\in_array($name, $viberFields, true))
		{
			Log::add('onCustomFieldsAfterPrepareField: '.$field->name.' = '.$value, Log::DEBUG, 'plg_fields_clickablecontactfields');
			$num   = $sanitizeDigits($value);
			$link  = 'viber://chat?number=%' . $num ;
			$value = '<a href="'.$link.'" target="_blank" rel="noopener">'.htmlspecialchars($value).'</a>';
		}
		// Facebook
		elseif (\in_array($name, $facebookFields, true))
		{
			Log::add('onCustomFieldsAfterPrepareField: '.$field->name.' = '.$value, Log::DEBUG, 'plg_fields_clickablecontactfields');
			$p             = trim($value);
			$pageLink      = 'https://www.facebook.com/' . urlencode(ltrim($p, '@'));
			$messengerLink = 'https://m.me/' . urlencode(ltrim($p, '@'));
			$value         = '<a href="'.$pageLink.'" target="_blank" rel="noopener">'.htmlspecialchars($value).'</a> (<a href="'.$messengerLink.'" target="_blank" rel="noopener">Messenger</a>)';
		}
	}
}
