<?php
class ControllerProductSpecial extends Controller {
	public function index() {
		$this->load->language('product/special');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = (int)$this->request->get['limit'];
		} else {
			$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/special', $url)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

       $data['text_sale'] = $this->language->get('text_sale');
       $data['text_new'] = $this->language->get('text_new');
       
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');


      $data['text_sale'] = $this->language->get('text_sale'); 
      $data['text_new'] = $this->language->get('text_new');
     
      
		$data['button_cart'] = $this->language->get('button_cart');

       $data['text_quick'] = $this->language->get('text_quick');
       
 
        $data['text_option'] = $this->language->get('text_option');
       $data['text_select'] = $this->language->get('text_select');
       $data['button_upload'] = $this->language->get('button_upload');
       $data['text_loading'] = $this->language->get('text_loading');
       
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');
		$data['button_continue'] = $this->language->get('button_continue');

		$data['compare'] = $this->url->link('product/compare');


       $data['label_sale'] = $this->config->get('config_label_sale');
        $data['label_discount'] = $this->config->get('config_label_discount');
        $data['label_new'] = $this->config->get('config_label_new');
        if ($this->config->get('config_label_new')) {
       $product_new = $this->model_catalog_product->getLatestProducts($this->config->get('config_label_new_limit'));
       }
       
		$data['products'] = array();
 
        $lim_last=5; 
        $filter_data = array(
          'sort'  => 'p.date_added',
          'order' => 'DESC',
          'start' => 0,
         'limit' => $lim_last
        );
        $results1 = $this->model_catalog_product->getProducts($filter_data);
        $last_array = array();
        foreach ($results1 as $result) {
        $last_array[] = $result['product_id'];      
        };
        
      

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);

		$product_total = $this->model_catalog_product->getTotalProductSpecials();

		$results = $this->model_catalog_product->getProductSpecials($filter_data);

		foreach ($results as $result) {

       if ($this->config->get('config_label_new')) {
       $label_new = 0;
       foreach ($product_new as $product_new_id => $product) {
       if ($product_new[$product_new_id]['product_id'] == $result['product_id']) {
       $label_new = 1;
       break;
        }
       }
       }
       
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
			}

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$price = false;
			}

			if ((float)$result['special']) {
				$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

       $label_discount = '-' . (int)(100 - ($result['special'] * 100 / $result['price'])) . '%';
       
			} else {
				$special = false;

       $label_discount = false;
        
			}

			if ($this->config->get('config_tax')) {
				$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
			} else {
				$tax = false;
			}

			if ($this->config->get('config_review_status')) {
				$rating = (int)$result['rating'];
			} else {
				$rating = false;
			}


       $options = array();
       foreach ($this->model_catalog_product->getProductOptions($result['product_id']) as $option) {
       $product_option_value_data = array();
       foreach ($option['product_option_value'] as $option_value) {
        if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
        if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
        $price_option = $this->currency->format($this->tax->calculate($option_value['price'], $result['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
        } else {
        $price_option = false;
        }
       $product_option_value_data[] = array(
       'product_option_value_id' => $option_value['product_option_value_id'],
        'option_value_id'         => $option_value['option_value_id'],
        'name'                    => $option_value['name'],
       'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
       'price'                   => $price_option,
       'price_prefix'            => $option_value['price_prefix']
        );
        }
       }
       $options[] = array(
       'product_option_id'    => $option['product_option_id'],
       'product_option_value' => $product_option_value_data,
       'option_id'            => $option['option_id'],
       'name'                 => $option['name'],
        'type'                 => $option['type'],
        'value'                => $option['value'],
       'required'             => $option['required']
       );
        }
       

       $desc = html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8');
        $pos = strpos($desc,'<iframe');
       if (is_int($pos)) {
       $pos2 = strpos($desc, '</iframe>') + 9;
       $video = substr($desc, $pos, $pos2);
        $quick_descr = str_replace($video, '', $desc);
        } else{
       $quick_descr = $desc;
       }
       
			$data['products'][] = array(
				'product_id'  => $result['product_id'],
				'thumb'       => $image,

       'img-width'  => $this->config->get($this->config->get('config_theme') . '_image_related_width'),
        'img-height' => $this->config->get($this->config->get('config_theme') . '_image_related_height'),
       
				'name'        => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
				'price'       => $price,
				'special'     => $special,

       'label_discount' => $label_discount,
        'label_new' => $this->config->get('config_label_new') ? $label_new : 0,
       
  'last_array' => $last_array,  'limit_last' => $lim_last, 
				'tax'         => $tax,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'rating'      => $result['rating'],

       'author'        => $result['manufacturer'],
       'description1'  => $quick_descr,
        'manufacturers' =>$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id']),
        'model'         => $result['model'],
        
				'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)

       ,
       'options'     => $options
       
			);
		}

		$url = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('product/special', 'sort=p.sort_order&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('product/special', 'sort=pd.name&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('product/special', 'sort=pd.name&order=DESC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'ps.price-ASC',
			'href'  => $this->url->link('product/special', 'sort=ps.price&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'ps.price-DESC',
			'href'  => $this->url->link('product/special', 'sort=ps.price&order=DESC' . $url)
		);

		if ($this->config->get('config_review_status')) {
			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_desc'),
				'value' => 'rating-DESC',
				'href'  => $this->url->link('product/special', 'sort=rating&order=DESC' . $url)
			);

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_rating_asc'),
				'value' => 'rating-ASC',
				'href'  => $this->url->link('product/special', 'sort=rating&order=ASC' . $url)
			);
		}

		$data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/special', 'sort=p.model&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_model_desc'),
			'value' => 'p.model-DESC',
			'href'  => $this->url->link('product/special', 'sort=p.model&order=DESC' . $url)
		);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['limits'] = array();

		$limits = array_unique(array($this->config->get($this->config->get('config_theme') . '_product_limit'), 25, 50, 75, 100));

		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('product/special', $url . '&limit=' . $value)
			);
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('product/special', $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
		    $this->document->addLink($this->url->link('product/special', '', true), 'canonical');
		} elseif ($page == 2) {
		    $this->document->addLink($this->url->link('product/special', '', true), 'prev');
		} else {
		    $this->document->addLink($this->url->link('product/special', 'page='. ($page - 1), true), 'prev');
		}

		if ($limit && ceil($product_total / $limit) > $page) {
		    $this->document->addLink($this->url->link('product/special', 'page='. ($page + 1), true), 'next');
		}

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('product/special', $data));
	}
}