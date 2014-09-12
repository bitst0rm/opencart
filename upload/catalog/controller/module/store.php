<?php   
class ControllerModuleStore extends Controller {
	protected function index() {
		$status = true;
		
		if ($this->config->get('store_admin')) {
			$this->load->library('user');
		
			$this->user = new User($this->registry);
			
			$status = $this->user->isLogged();
		}
		
		if ($status) {
			$this->language->load('module/store');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_store'] = $this->language->get('text_store');
			
			$this->data['store_id'] = '';
			
			$this->data['stores'] = array();
			
			$this->load->model('setting/setting');
			
			$store = $this->model_setting_setting->getSetting('config', 0);

			$this->data['stores'][] = array(
				'store_id' => 0,
				'name'     => $store['config_name'],
				'url'      => $store['config_secure'] ? HTTPS_SERVER : HTTP_SERVER . 'index.php?route=common/home&session_id=' . $this->session->getId()
			);
			
			$this->load->model('setting/store');
			
			$results = $this->model_setting_store->getStores();
			
			foreach ($results as $result) {
				$store = $this->model_setting_setting->getSetting('config', $result['store_id']);
				$this->data['stores'][] = array(
					'store_id' => $result['store_id'],
					'name'     => $result['name'],
					'url'      => $store['config_secure'] ? $result['ssl'] : $result['url'] . 'index.php?route=common/home&session_id=' . $this->session->getId()
				);
			}
	
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/store.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/store.tpl';
			} else {
				$this->template = 'default/template/module/store.tpl';
			}
			
			$this->render();
		}
	}
}
?>