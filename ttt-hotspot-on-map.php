/**
 * ============================================
 * TTT Hotspot on Map - PHP 主程序
 * @version     1.0.20
 * @description 修复手机三Bug: mobile空值默认bottom + aln首次百分比解析 + 水平滚动抑制
 * ============================================
 */

add_action( 'elementor/widgets/register', function ( $widgets_manager ) {

    if ( ! class_exists( '\Elementor\Widget_Base' ) ) return;

    if ( ! class_exists( 'TTT_Hotspot_On_Map_Widget' ) ) {

    class TTT_Hotspot_On_Map_Widget extends \Elementor\Widget_Base {

        public function get_name()           { return 'ttt_hotspot_on_map'; }
        public function get_title()          { return 'TTT Hotspot on Map'; }
        public function get_icon()           { return 'eicon-map-pin'; }
        public function get_categories()     { return [ 'general' ]; }
        public function get_keywords()       { return [ 'map', 'hotspot', 'svg', 'world', 'location', 'ttt', 'bezier', 'orbit' ]; }
        public function get_style_depends()  { return [ 'ttt-hotspot-on-map' ]; }
        public function get_script_depends() { return [ 'ttt-hotspot-on-map' ]; }

        public static function get_location_presets() {
            return [
                '' => ['label'=>'-- 选择位置 --','x'=>0,'y'=>0,'type'=>''],
                'shanghai'=>['label'=>'上海 (Shanghai)','x'=>72.1,'y'=>38.0,'type'=>'city'],
                'beijing'=>['label'=>'北京 (Beijing)','x'=>70.5,'y'=>33.0,'type'=>'city'],
                'tokyo'=>['label'=>'东京 (Tokyo)','x'=>78.8,'y'=>35.5,'type'=>'city'],
                'seoul'=>['label'=>'首尔 (Seoul)','x'=>74.2,'y'=>32.8,'type'=>'city'],
                'hongkong'=>['label'=>'香港 (Hong Kong)','x'=>71.3,'y'=>41.5,'type'=>'city'],
                'taipei'=>['label'=>'台北 (Taipei)','x'=>72.5,'y'=>39.3,'type'=>'city'],
                'singapore'=>['label'=>'新加坡 (Singapore)','x'=>68.8,'y'=>48.4,'type'=>'city'],
                'bangkok'=>['label'=>'曼谷 (Bangkok)','x'=>66.8,'y'=>44.6,'type'=>'city'],
                'kuala_lumpur'=>['label'=>'吉隆坡 (Kuala Lumpur)','x'=>67.2,'y'=>47.5,'type'=>'city'],
                'jakarta'=>['label'=>'雅加达 (Jakarta)','x'=>69.5,'y'=>53.0,'type'=>'city'],
                'ho_chi_minh'=>['label'=>'胡志明市 (HCMC)','x'=>69.0,'y'=>45.5,'type'=>'city'],
                'manila'=>['label'=>'马尼拉 (Manila)','x'=>73.5,'y'=>44.0,'type'=>'city'],
                'mumbai'=>['label'=>'孟买 (Mumbai)','x'=>59.5,'y'=>42.0,'type'=>'city'],
                'delhi'=>['label'=>'新德里 (New Delhi)','x'=>63.0,'y'=>36.5,'type'=>'city'],
                'dubai'=>['label'=>'迪拜 (Dubai)','x'=>54.5,'y'=>38.0,'type'=>'city'],
                'riyadh'=>['label'=>'利雅得 (Riyadh)','x'=>52.5,'y'=>40.5,'type'=>'city'],
                'istanbul'=>['label'=>'伊斯坦布尔 (Istanbul)','x'=>48.0,'y'=>28.0,'type'=>'city'],
                'moscow'=>['label'=>'莫斯科 (Moscow)','x'=>47.0,'y'=>21.5,'type'=>'city'],
                'london'=>['label'=>'伦敦 (London)','x'=>43.0,'y'=>25.0,'type'=>'city'],
                'paris'=>['label'=>'巴黎 (Paris)','x'=>44.0,'y'=>27.2,'type'=>'city'],
                'berlin'=>['label'=>'柏林 (Berlin)','x'=>45.5,'y'=>24.0,'type'=>'city'],
                'rome'=>['label'=>'罗马 (Rome)','x'=>45.2,'y'=>29.5,'type'=>'city'],
                'madrid'=>['label'=>'马德里 (Madrid)','x'=>41.0,'y'=>29.0,'type'=>'city'],
                'amsterdam'=>['label'=>'阿姆斯特丹 (Amsterdam)','x'=>43.8,'y'=>23.5,'type'=>'city'],
                'new_york'=>['label'=>'纽约 (New York)','x'=>21.8,'y'=>28.5,'type'=>'city'],
                'los_angeles'=>['label'=>'洛杉矶 (Los Angeles)','x'=>12.8,'y'=>30.2,'type'=>'city'],
                'chicago'=>['label'=>'芝加哥 (Chicago)','x'=>19.5,'y'=>26.5,'type'=>'city'],
                'toronto'=>['label'=>'多伦多 (Toronto)','x'=>21.5,'y'=>26.0,'type'=>'city'],
                'san_francisco'=>['label'=>'旧金山 (San Francisco)','x'=>11.0,'y'=>29.0,'type'=>'city'],
                'mexico_city'=>['label'=>'墨西哥城 (Mexico City)','x'=>16.5,'y'=>40.0,'type'=>'city'],
                'sao_paulo'=>['label'=>'圣保罗 (São Paulo)','x'=>28.0,'y'=>60.0,'type'=>'city'],
                'buenos_aires'=>['label'=>'布宜诺斯艾利斯','x'=>27.5,'y'=>64.0,'type'=>'city'],
                'lima'=>['label'=>'利马 (Lima)','x'=>22.0,'y'=>54.0,'type'=>'city'],
                'sydney'=>['label'=>'悉尼 (Sydney)','x'=>80.0,'y'=>65.2,'type'=>'city'],
                'melbourne'=>['label'=>'墨尔本 (Melbourne)','x'=>79.2,'y'=>67.0,'type'=>'city'],
                'auckland'=>['label'=>'奥克兰 (Auckland)','x'=>82.5,'y'=>68.5,'type'=>'city'],
                'cairo'=>['label'=>'开罗 (Cairo)','x'=>49.5,'y'=>35.5,'type'=>'city'],
                'lagos'=>['label'=>'拉各斯 (Lagos)','x'=>44.0,'y'=>45.5,'type'=>'city'],
                'nairobi'=>['label'=>'内罗毕 (Nairobi)','x'=>50.5,'y'=>52.0,'type'=>'city'],
                'johannesburg'=>['label'=>'约翰内斯堡','x'=>47.5,'y'=>65.5,'type'=>'city'],
                'asia'=>['label'=>'亚洲 (Asia)','x'=>68.0,'y'=>38.0,'type'=>'continent'],
                'europe'=>['label'=>'欧洲 (Europe)','x'=>46.0,'y'=>22.0,'type'=>'continent'],
                'north_america'=>['label'=>'北美洲 (North America)','x'=>18.0,'y'=>28.0,'type'=>'continent'],
                'south_america'=>['label'=>'南美洲 (South America)','x'=>25.0,'y'=>55.0,'type'=>'continent'],
                'africa'=>['label'=>'非洲 (Africa)','x'=>48.0,'y'=>48.0,'type'=>'continent'],
                'oceania'=>['label'=>'大洋洲 (Oceania)','x'=>78.0,'y'=>62.0,'type'=>'continent'],
                'middle_east'=>['label'=>'中东 (Middle East)','x'=>53.0,'y'=>36.0,'type'=>'continent'],
                'central_asia'=>['label'=>'中亚 (Central Asia)','x'=>60.0,'y'=>30.0,'type'=>'continent'],
            ];
        }

        protected function register_controls() {

            $this->start_controls_section( 'section_map', [ 'label' => '地图设置', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ] );
            $this->add_control( 'map_image', [ 'label' => '世界地图 SVG', 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => [ 'url' => 'https://raw.githubusercontent.com/tttworks/ttt-hotspot-on-map/main/assets/world-map.svg' ] ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_hotspots', [ 'label' => '热点标记', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ] );
            $repeater = new \Elementor\Repeater();
            $repeater->add_control( 'hotspot_label', [ 'label' => '标记名称', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '标记 1', 'label_block' => true ] );
            $repeater->add_control( 'hotspot_type', [ 'label' => '标记类型', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'center', 'options' => [ 'center' => '中心点', 'spoke' => '分散点' ] ] );
            $repeater->add_control( 'location_preset', [ 'label' => '预设位置', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => '', 'options' => self::get_preset_options(), 'description' => '注意：该功能暂不可用。' ] );
            $repeater->add_control( 'location_x', [ 'label' => 'X 坐标 (%)', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0, 'max' => 100, 'step' => 0.01 ], 'default' => [ 'size' => 72.10 ] ] );
            $repeater->add_control( 'location_y', [ 'label' => 'Y 坐标 (%)', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0, 'max' => 100, 'step' => 0.01 ], 'default' => [ 'size' => 38.00 ] ] );
            $repeater->add_control( 'marker_draggable', [ 'label' => '编辑器中拖拽微调', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'no', 'return_value' => 'yes', 'description' => '拖拽松手后坐标自动持久化（仅编辑器）。注意：该功能暂不可用。' ] );
            $repeater->add_control( 'connect_to', [ 'label' => '连接到（中心点名称）', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => '', 'label_block' => true, 'condition' => [ 'hotspot_type' => 'spoke' ] ] );
            $repeater->add_control( 'curve_curvature', [ 'label' => '曲线曲度 (×标准弧度)', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0.01, 'max' => 10, 'step' => 0.01 ], 'default' => [ 'size' => 10 ], 'condition' => [ 'hotspot_type' => 'spoke' ] ] );
            $repeater->add_control( 'curve_bend', [ 'label' => '弯曲方向', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'forward', 'options' => [ 'forward' => '正向弯曲', 'reverse' => '反向弯曲' ], 'condition' => [ 'hotspot_type' => 'spoke' ] ] );
            $repeater->add_control( 'hotspot_icon', [ 'label' => '自定义图标', 'type' => \Elementor\Controls_Manager::MEDIA, 'default' => [ 'url' => '' ] ] );
            $repeater->add_responsive_control( 'label_position', [ 'label' => '文字位置', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'right', 'tablet_default' => 'right', 'mobile_default' => 'bottom', 'options' => [ 'top' => '上', 'right' => '右', 'bottom' => '下', 'left' => '左' ], 'description' => '桌面/平板常用"右"，手机端可改为"下"避免文字越界' ] );
            $repeater->add_control( 'show_info_popup', [ 'label' => '点击显示信息窗', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'no', 'return_value' => 'yes' ] );
            $repeater->add_control( 'info_content', [ 'label' => '信息窗内容', 'type' => \Elementor\Controls_Manager::TEXTAREA, 'rows' => 3, 'default' => '', 'condition' => [ 'show_info_popup' => 'yes' ], 'dynamic' => [ 'active' => true ] ] );
            $this->add_control( 'hotspots', [
                'label' => '热点列表', 'type' => \Elementor\Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(),
                'title_field' => '{{{ hotspot_label }}} ({{{ hotspot_type }}})',
                'default' => [
                    [ 'hotspot_label' => '上海', 'hotspot_type' => 'center', 'location_preset' => 'shanghai', 'hotspot_icon' => [ 'url' => '' ], 'location_x' => [ 'size' => 72.10 ], 'location_y' => [ 'size' => 38.00 ] ],
                    [ 'hotspot_label' => '亚洲', 'hotspot_type' => 'spoke',  'location_preset' => 'asia',    'location_x' => [ 'size' => 68.00 ], 'location_y' => [ 'size' => 38.00 ], 'connect_to' => '上海', 'curve_curvature' => [ 'size' => 10 ], 'curve_bend' => 'forward' ],
                ],
            ] );
            $this->end_controls_section();

            // 全局脉冲层
            $this->start_controls_section( 'section_global_pulse', [ 'label' => '全局脉冲层', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ] );
            $this->add_control( 'global_enable_pulse', [ 'label' => '显示脉冲层', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes', 'description' => '控制所有热点的脉冲层显示。' ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_curve_defaults', [ 'label' => '连接曲线', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ] );
            $this->add_control( 'default_curvature', [ 'label' => '标准弧度', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0.01, 'max' => 3.00, 'step' => 0.01 ], 'default' => [ 'size' => 0.02 ] ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_orbit', [ 'label' => '轨道动画', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ] );
            $this->add_control( 'orbit_mode_select', [ 'label' => '轨道动画模式', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'both', 'options' => [ 'progress' => '轨道进度条运动', 'dot' => '轨道点运动', 'both' => '轨道进度+轨道点运动', 'off' => '关闭' ] ] );
            $this->add_control( 'orbit_direction', [ 'label' => '动画方向', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'spoke_to_center', 'options' => [ 'spoke_to_center' => '分散点 → 中心点', 'center_to_spoke' => '中心点 → 分散点', 'bidirectional' => '双向（来回）' ], 'condition' => [ 'orbit_mode_select!' => 'off' ] ] );
            $this->add_control( 'orbit_speed', [ 'label' => '运动速度 (秒)', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0.5, 'max' => 10, 'step' => 0.5 ], 'default' => [ 'size' => 5 ], 'condition' => [ 'orbit_mode_select!' => 'off' ] ] );
            $this->add_control( 'orbit_arrange_mode', [ 'label' => '编排模式', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'simultaneous', 'options' => [ 'simultaneous' => '同时运行', 'sequential' => '顺序运行' ], 'condition' => [ 'orbit_mode_select!' => 'off' ] ] );
            $this->add_control( 'orbit_order', [ 'label' => '顺序方向', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'clockwise', 'options' => [ 'clockwise' => '顺时针', 'counter_clockwise' => '逆时针' ], 'condition' => [ 'orbit_mode_select!' => 'off', 'orbit_arrange_mode' => 'sequential' ] ] );
            $this->add_control( 'orbit_speed_mode', [ 'label' => '速度模式', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'uniform', 'options' => [ 'uniform' => '同时抵达', 'sync_arrival' => '匀速抵达' ], 'condition' => [ 'orbit_mode_select!' => 'off', 'orbit_arrange_mode' => 'simultaneous' ] ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_interaction', [ 'label' => '交互设置', 'tab' => \Elementor\Controls_Manager::TAB_CONTENT ] );
            $this->add_control( 'enable_hover_highlight', [ 'label' => '悬停高亮', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ] );
            $this->add_control( 'enable_click_popup', [ 'label' => '点击弹出信息窗', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_container_style', [ 'label' => '容器样式', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_control( 'map_border_radius', [ 'label' => '圆角', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0, 'max' => 50 ], 'default' => [ 'size' => 0 ], 'selectors' => [ '{{WRAPPER}} .zhom-map-wrapper' => 'border-radius: {{SIZE}}px;' ] ] );
            $this->add_control( 'map_border_color', [ 'label' => '边框颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#e0e0e0', 'selectors' => [ '{{WRAPPER}} .zhom-map-wrapper' => 'border-color: {{VALUE}};' ] ] );
            $this->add_control( 'map_border_width', [ 'label' => '边框宽度', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0, 'max' => 10 ], 'default' => [ 'size' => 1 ], 'selectors' => [ '{{WRAPPER}} .zhom-map-wrapper' => 'border-style: solid; border-width: {{SIZE}}px;' ] ] );
            $this->add_responsive_control( 'map_margin', [ 'label' => '外边距', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em', '%', 'rem' ], 'default' => [ 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => false ], 'selectors' => [ '{{WRAPPER}} .zhom-map-outer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_marker_style', [ 'label' => '标记样式', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_control( 'marker_color', [ 'label' => '标记颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#59A498', 'selectors' => [ '{{WRAPPER}} .zhom-marker-dot' => 'background-color: {{VALUE}};' ] ] );
            $this->add_responsive_control( 'marker_size', [ 'label' => '标记大小', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 4, 'max' => 40 ], 'default' => [ 'size' => 8 ], 'selectors' => [ '{{WRAPPER}} .zhom-marker-dot' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
            $this->add_control( 'pulse_color', [ 'label' => '脉冲颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => 'rgba(89,164,152,0.35)', 'selectors' => [ '{{WRAPPER}} .zhom-pulse-ring' => 'background-color: {{VALUE}};' ] ] );
            $this->add_responsive_control( 'pulse_size', [ 'label' => '脉冲大小', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 10, 'max' => 80 ], 'default' => [ 'size' => 18 ], 'selectors' => [ '{{WRAPPER}} .zhom-pulse-ring' => 'width: {{SIZE}}px; height: {{SIZE}}px;', '{{WRAPPER}} .zhom-pulse-ring::after' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
            $this->add_control( 'pulse_duration', [ 'label' => '脉冲周期', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 1, 'max' => 6, 'step' => 0.5 ], 'default' => [ 'size' => 1.2 ], 'selectors' => [ '{{WRAPPER}} .zhom-pulse-ring::after' => 'animation-duration: {{SIZE}}s;' ] ] );
            $this->add_responsive_control( 'pulse_radius', [ 'label' => '脉冲辐射范围 (倍)', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 1, 'max' => 5, 'step' => 0.1 ], 'default' => [ 'size' => 3 ], 'selectors' => [ '{{WRAPPER}} .zhom-pulse-ring::after' => '--zhom-pulse-scale: {{SIZE}};' ] ] );
            $this->add_responsive_control( 'dot_inner_size', [ 'label' => '标记中心白点大小 (px)', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0, 'max' => 20, 'step' => 0.5 ], 'default' => [ 'size' => 0 ], 'selectors' => [ '{{WRAPPER}} .zhom-marker-dot::after' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
            $this->add_control( 'dot_inner_color', [ 'label' => '标记中心白点颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .zhom-marker-dot::after' => 'background-color: {{VALUE}};' ] ] );
            $this->add_responsive_control( 'custom_icon_size', [ 'label' => '图标大小', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 12, 'max' => 64 ], 'default' => [ 'size' => 24 ], 'selectors' => [ '{{WRAPPER}} .zhom-marker-icon img' => 'width: {{SIZE}}px; height: {{SIZE}}px;' ] ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_text_style', [ 'label' => '文本框样式', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->start_controls_tabs( 'text_tabs' );
            $this->start_controls_tab( 'text_normal_tab', [ 'label' => 'Normal' ] );
            $this->add_control( 'text_gap', [ 'label' => '与图标间距', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 2, 'max' => 40 ], 'default' => [ 'size' => 8 ], 'selectors' => [ '{{WRAPPER}} .zhom-marker' => 'gap: {{SIZE}}px;' ] ] );
            $this->add_control( 'text_color', [ 'label' => '文字颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#333333', 'selectors' => [ '{{WRAPPER}} .zhom-marker-label' => 'color: {{VALUE}};' ] ] );
            $this->add_control( 'text_bg_color', [ 'label' => '背景色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => 'rgba(255,255,255,0.92)', 'selectors' => [ '{{WRAPPER}} .zhom-marker-label' => 'background-color: {{VALUE}};' ] ] );
            $this->add_control( 'text_opacity', [ 'label' => '文本框透明度', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ], 'default' => [ 'size' => 1 ], 'selectors' => [ '{{WRAPPER}} .zhom-marker-label' => 'opacity: {{SIZE}};' ] ] );
            $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'text_typography', 'selector' => '{{WRAPPER}} .zhom-marker-label', 'fields_options' => [ 'font_family' => [ 'default' => 'Montserrat' ] ] ] );
            $this->add_control( 'text_padding', [ 'label' => '内边距', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'default' => [ 'top' => '4', 'right' => '10', 'bottom' => '4', 'left' => '10', 'unit' => 'px', 'isLinked' => false ], 'selectors' => [ '{{WRAPPER}} .zhom-marker-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_control( 'text_margin', [ 'label' => '外边距', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'default' => [ 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'unit' => 'px', 'isLinked' => false ], 'selectors' => [ '{{WRAPPER}} .zhom-marker-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_control( 'text_border_radius', [ 'label' => '圆角', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0, 'max' => 20 ], 'default' => [ 'size' => 4 ], 'selectors' => [ '{{WRAPPER}} .zhom-marker-label' => 'border-radius: {{SIZE}}px;' ] ] );
            $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [ 'name' => 'text_shadow', 'selector' => '{{WRAPPER}} .zhom-marker-label' ] );
            $this->add_responsive_control( 'text_max_width', [ 'label' => '最大宽度', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 60, 'max' => 600 ], 'default' => [ 'size' => 200 ], 'selectors' => [ '{{WRAPPER}} .zhom-marker-label' => 'max-width: {{SIZE}}px;' ] ] );
            $this->end_controls_tab();
            $this->start_controls_tab( 'text_hover_tab', [ 'label' => 'Hover' ] );
            $this->add_control( 'text_hover_color', [ 'label' => '文字颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#01333d', 'selectors' => [ '{{WRAPPER}} .zhom-marker.zhom-hover .zhom-marker-label' => 'color: {{VALUE}};' ] ] );
            $this->add_control( 'text_hover_bg', [ 'label' => '背景色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => 'rgba(1,51,61,0.08)', 'selectors' => [ '{{WRAPPER}} .zhom-marker.zhom-hover .zhom-marker-label' => 'background-color: {{VALUE}};' ] ] );
            $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [ 'name' => 'text_hover_shadow', 'selector' => '{{WRAPPER}} .zhom-marker.zhom-hover .zhom-marker-label' ] );
            $this->end_controls_tab();
            $this->end_controls_tabs();
            $this->end_controls_section();

            $this->start_controls_section( 'section_curve_style', [ 'label' => '连接曲线样式', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_control( 'curve_color', [ 'label' => '曲线颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => 'rgba(1,51,61,0.2)' ] );
            $this->add_control( 'curve_width', [ 'label' => '曲线粗细', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0.5, 'max' => 5, 'step' => 0.5 ], 'default' => [ 'size' => 1 ] ] );
            $this->add_control( 'curve_dash_length', [ 'label' => '虚线长度', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 1, 'max' => 20 ], 'default' => [ 'size' => 3 ] ] );
            $this->add_control( 'curve_dash_gap', [ 'label' => '虚线间隔', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 1, 'max' => 20 ], 'default' => [ 'size' => 4 ] ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_orbit_dot_style', [ 'label' => '轨道点样式', 'tab' => \Elementor\Controls_Manager::TAB_STYLE, 'condition' => [ 'orbit_mode_select!' => 'off' ] ] );
            $this->add_control( 'orbit_dot_color', [ 'label' => '轨道点颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => 'rgba(1,51,61,1)' ] );
            $this->add_control( 'orbit_dot_size', [ 'label' => '轨道点大小', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 4, 'max' => 20 ], 'default' => [ 'size' => 8 ] ] );
            $this->add_control( 'orbit_dot_glow', [ 'label' => '光晕', 'type' => \Elementor\Controls_Manager::SWITCHER, 'default' => 'yes', 'return_value' => 'yes' ] );
            $this->add_control( 'orbit_trail_style', [ 'label' => '轨迹样式', 'type' => \Elementor\Controls_Manager::SELECT, 'default' => 'solid', 'options' => [ 'solid' => '实线', 'dashed' => '虚线', 'none' => '隐藏' ] ] );
            $this->add_control( 'orbit_trail_color', [ 'label' => '轨迹颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#59A498' ] );
            $this->add_control( 'orbit_trail_width', [ 'label' => '轨迹粗细', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0.5, 'max' => 4, 'step' => 0.5 ], 'default' => [ 'size' => 1 ] ] );
            $this->end_controls_section();

            $this->start_controls_section( 'section_popup_style', [ 'label' => '信息窗样式', 'tab' => \Elementor\Controls_Manager::TAB_STYLE ] );
            $this->add_control( 'popup_bg_color', [ 'label' => '背景色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#ffffff', 'selectors' => [ '{{WRAPPER}} .zhom-info-popup' => 'background-color: {{VALUE}};' ] ] );
            $this->add_control( 'popup_text_color', [ 'label' => '文字颜色', 'type' => \Elementor\Controls_Manager::COLOR, 'default' => '#333333', 'selectors' => [ '{{WRAPPER}} .zhom-info-popup' => 'color: {{VALUE}};' ] ] );
            $this->add_group_control( \Elementor\Group_Control_Typography::get_type(), [ 'name' => 'popup_typography', 'selector' => '{{WRAPPER}} .zhom-info-popup', 'fields_options' => [ 'font_family' => [ 'default' => 'Montserrat' ] ] ] );
            $this->add_control( 'popup_border_radius', [ 'label' => '圆角', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 0, 'max' => 20 ], 'default' => [ 'size' => 8 ], 'selectors' => [ '{{WRAPPER}} .zhom-info-popup' => 'border-radius: {{SIZE}}px;' ] ] );
            $this->add_control( 'popup_padding', [ 'label' => '内边距', 'type' => \Elementor\Controls_Manager::DIMENSIONS, 'size_units' => [ 'px', 'em' ], 'default' => [ 'top' => '12', 'right' => '16', 'bottom' => '12', 'left' => '16', 'unit' => 'px', 'isLinked' => false ], 'selectors' => [ '{{WRAPPER}} .zhom-info-popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ] ] );
            $this->add_group_control( \Elementor\Group_Control_Box_Shadow::get_type(), [ 'name' => 'popup_shadow', 'selector' => '{{WRAPPER}} .zhom-info-popup' ] );
            $this->add_control( 'popup_min_width', [ 'label' => '最小宽度', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 120, 'max' => 500 ], 'default' => [ 'size' => 200 ], 'selectors' => [ '{{WRAPPER}} .zhom-info-popup' => 'min-width: {{SIZE}}px;' ] ] );
            $this->add_control( 'popup_max_width', [ 'label' => '最大宽度', 'type' => \Elementor\Controls_Manager::SLIDER, 'range' => [ 'min' => 180, 'max' => 600 ], 'default' => [ 'size' => 320 ], 'selectors' => [ '{{WRAPPER}} .zhom-info-popup' => 'max-width: {{SIZE}}px;' ] ] );
            $this->end_controls_section();
        }

        private static function get_preset_options() {
            $p = self::get_location_presets(); $o = []; $ci = []; $co = []; $ot = [];
            foreach ($p as $k=>$v) { if ($k==='') $ot[$k]=$v['label']; elseif ($v['type']==='city') $ci[$k]=$v['label']; elseif ($v['type']==='continent') $co[$k]=$v['label']; }
            if (!empty($ot)) $o = array_merge($o, $ot);
            if (!empty($ci)) { $o['── 城市 ──']=''; $o = array_merge($o, $ci); }
            if (!empty($co)) { $o['── 大洲 ──']=''; $o = array_merge($o, $co); }
            return $o;
        }

        protected function render() {
            $s = $this->get_settings_for_display();
            $mu = isset($s['map_image']['url']) && !empty($s['map_image']['url']) ? $s['map_image']['url'] : '';
            $oms = isset($s['orbit_mode_select']) ? $s['orbit_mode_select'] : 'both';
            $opr = ($oms==='progress'||$oms==='both'); $odot = ($oms==='dot'||$oms==='both');
            $odr = isset($s['orbit_direction']) ? $s['orbit_direction'] : 'spoke_to_center';
            $osp = isset($s['orbit_speed']['size']) ? floatval($s['orbit_speed']['size']) : 5;
            $oar = isset($s['orbit_arrange_mode']) ? $s['orbit_arrange_mode'] : 'simultaneous';
            $ood = isset($s['orbit_order']) ? $s['orbit_order'] : 'clockwise';
            $osm = isset($s['orbit_speed_mode']) ? $s['orbit_speed_mode'] : 'uniform';
            $eh  = (isset($s['enable_hover_highlight']) && $s['enable_hover_highlight']==='yes');
            $ec  = (isset($s['enable_click_popup']) && $s['enable_click_popup']==='yes');
            $dc  = isset($s['default_curvature']['size']) ? floatval($s['default_curvature']['size']) : 0.02;
            $gp  = (isset($s['global_enable_pulse']) && $s['global_enable_pulse']==='yes');
            $cc  = isset($s['curve_color']) ? $s['curve_color'] : 'rgba(1,51,61,0.2)';
            $cw  = isset($s['curve_width']['size']) ? floatval($s['curve_width']['size']) : 1;
            $cdl = isset($s['curve_dash_length']['size']) ? floatval($s['curve_dash_length']['size']) : 3;
            $cdg = isset($s['curve_dash_gap']['size']) ? floatval($s['curve_dash_gap']['size']) : 4;
            $odc = isset($s['orbit_dot_color']) ? $s['orbit_dot_color'] : 'rgba(1,51,61,1)';
            $ods = isset($s['orbit_dot_size']['size']) ? floatval($s['orbit_dot_size']['size']) : 8;
            $odg = (!isset($s['orbit_dot_glow']) || $s['orbit_dot_glow']==='yes');
            $ots = isset($s['orbit_trail_style']) ? $s['orbit_trail_style'] : 'solid';
            $otc = isset($s['orbit_trail_color']) ? $s['orbit_trail_color'] : '#59A498';
            $otw = isset($s['orbit_trail_width']['size']) ? floatval($s['orbit_trail_width']['size']) : 1;

            $hd = [];
            if (isset($s['hotspots']) && is_array($s['hotspots'])) {
                foreach ($s['hotspots'] as $h) {
                    $iu = isset($h['hotspot_icon']['url']) && !empty($h['hotspot_icon']['url']) ? $h['hotspot_icon']['url'] : '';
                    $x  = isset($h['location_x']['size']) ? floatval($h['location_x']['size']) : 50;
                    $y  = isset($h['location_y']['size']) ? floatval($h['location_y']['size']) : 50;
                    $cf = isset($h['curve_curvature']['size']) ? floatval($h['curve_curvature']['size']) : 10;
                    $nl = isset($h['hotspot_label']) ? $h['hotspot_label'] : '';
                    $hd[] = [
                        'label' => $nl, 'type' => isset($h['hotspot_type'])?$h['hotspot_type']:'spoke',
                        'x' => round($x,2), 'y' => round($y,2),
                        'draggable' => (isset($h['marker_draggable'])&&$h['marker_draggable']==='yes'),
                        'connect_to'=> isset($h['connect_to'])?trim($h['connect_to']):'',
                        'curvature' => $cf, 'bend' => isset($h['curve_bend'])?$h['curve_bend']:'forward',
                        'icon' => esc_url($iu), 'label_pos'=> isset($h['label_position'])?$h['label_position']:'right',
                        'label_pos_mobile'=> (isset($h['label_position_mobile']) && !empty($h['label_position_mobile'])) ? $h['label_position_mobile'] : 'bottom',
                        'show_popup'=> (isset($h['show_info_popup'])&&$h['show_info_popup']==='yes'),
                        'info_content'=> isset($h['info_content'])?$h['info_content']:'',
                    ];
                }
            }

            $wid = 'zhom-'.$this->get_id();
            echo '<div class="zhom-map-outer"><div id="'.esc_attr($wid).'" class="zhom-map-wrapper"';
            foreach ([
                'mapUrl'=>esc_url($mu),'orbitModeSel'=>$oms,'orbitProgress'=>$opr,'orbitDotShow'=>$odot,
                'orbitDirection'=>$odr,'orbitSpeed'=>$osp,'orbitArrange'=>$oar,'orbitOrder'=>$ood,
                'orbitSpeedMode'=>$osm,'enableHover'=>$eh,'enableClick'=>$ec,
                'defaultCurvature'=>$dc,'globalPulse'=>$gp,
                'curveColor'=>$cc,'curveWidth'=>$cw,'curveDashLength'=>$cdl,'curveDashGap'=>$cdg,
                'orbitDotColor'=>$odc,'orbitDotSize'=>$ods,'orbitDotGlow'=>$odg,
                'orbitTrailStyle'=>$ots,'orbitTrailColor'=>$otc,'orbitTrailWidth'=>$otw,
            ] as $k=>$v) {
                echo ' data-'.esc_attr(self::ck($k)).'="'.esc_attr(is_bool($v)?($v?'true':'false'):(string)$v).'"';
            }
            echo ' data-hotspots="'.esc_attr(wp_json_encode($hd)).'">';
            if (!empty($mu)) echo '<img class="zhom-map-image" src="'.esc_url($mu).'" alt="World Map" />';
            echo '<svg class="zhom-overlay" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet"></svg>';
            echo '<div class="zhom-markers-layer"></div>';
            echo '<svg class="zhom-orbit-layer" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet"></svg>';
            echo '</div></div>';
        }
        private static function ck($s){return strtolower(preg_replace('/([a-z])([A-Z])/','$1-$2',$s));}
    }
    }
    $widgets_manager->register(new TTT_Hotspot_On_Map_Widget());
});
