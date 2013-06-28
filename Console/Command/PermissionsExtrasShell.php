<?php
/**
 * Permissions Extras Shell.
 *
 * Enhances the existing Acl Shell with a few handy functions
 *
 * Copyright 2013, Kim Stacks
 * Singapore
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2013, Kim Stacks.
 * @link http://stacktogether.com
 * @author Kim Stacks <kim@stacktogether.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

App::uses('PermissionsExtras', 'PermissionsExtras.Lib');

/**
 * Shell for Permissions Extras
 *
 * @package		PermissionsExtras
 * @subpackage	PermissionsExtras.Console.Command
 */
class PermissionsExtrasShell extends Shell {

/**
 * Contains arguments parsed from the command line.
 *
 * @var array
 * @access public
 */
	public $args;

/**
 * PermissionsExtras instance
 */
	public $PermissionsExtras;

/**
 * Constructor
 */
	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		parent::__construct($stdout, $stderr, $stdin);
		$this->PermissionsExtras = new PermissionsExtras();
	}

/**
 * Start up And load Acl Component / Aco model
 *
 * @return void
 **/
	public function startup() {
		parent::startup();
		$this->PermissionsExtras->startup();
		$this->PermissionsExtras->Shell = $this;
	}

/**
 * Sync the ACO table
 *
 * @return void
 **/
	public function permissions_reset() {
		$this->PermissionsExtras->permissions_reset($this->params);
	}

/**
 * Updates the Aco Tree with new controller actions.
 *
 * @return void
 **/
	public function aco_update() {
		$this->PermissionsExtras->aco_update($this->params);
		return true;
	}

	public function getOptionParser() {
		$plugin = array(
			'short' => 'p',
			'help' => __('Plugin to process'),
			);
		return parent::getOptionParser()
			->description(__("Better manage, and easily synchronize you application's ACO tree"))
			->addSubcommand('aco_update', array(
				'parser' => array(
					'options' => compact('plugin'),
					),
				'help' => __('Add new ACOs for new controllers and actions. Does not remove nodes from the ACO table.')
			))->addSubcommand('aco_sync', array(
				'parser' => array(
					'options' => compact('plugin'),
					),
				'help' => __('Perform a full sync on the ACO table.' .
					'Will create new ACOs or missing controllers and actions.' .
					'Will also remove orphaned entries that no longer have a matching controller/action')
			))->addSubcommand('verify', array(
				'help' => __('Verify the tree structure of either your Aco or Aro Trees'),
				'parser' => array(
					'arguments' => array(
						'type' => array(
							'required' => true,
							'help' => __('The type of tree to verify'),
							'choices' => array('aco', 'aro')
						)
					)
				)
			))->addSubcommand('recover', array(
				'help' => __('Recover a corrupted Tree'),
				'parser' => array(
					'arguments' => array(
						'type' => array(
							'required' => true,
							'help' => __('The type of tree to recover'),
							'choices' => array('aco', 'aro')
						)
					)
				)
			));
	}

/**
 * Verify a Acl Tree
 *
 * @param string $type The type of Acl Node to verify
 * @access public
 * @return void
 */
	public function verify() {
		$this->PermissionsExtras->args = $this->args;
		return $this->PermissionsExtras->verify();
	}
/**
 * Recover an Acl Tree
 *
 * @param string $type The Type of Acl Node to recover
 * @access public
 * @return void
 */
	public function recover() {
		$this->PermissionsExtras->args = $this->args;
		$this->PermissionsExtras->recover();
	}
}
