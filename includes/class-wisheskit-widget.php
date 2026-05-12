<?php
if (!defined('ABSPATH')) {
    exit;
}

class WishesKit_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'wisheskit';
    }

    public function get_title() {
        return __('WishesKit', 'wisheskit');
    }

    public function get_icon() {
        return 'eicon-comments';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_style_depends() {
        return ['wisheskit-style'];
    }

    public function get_script_depends() {
        return ['wisheskit-script'];
    }

    protected function register_controls() {

        // === CONTENT SECTION ===
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('title_text', [
            'label' => __('Title', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Wishes',
        ]);

        $this->add_control('name_placeholder', [
            'label' => __('Name Placeholder', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Nama',
        ]);

        $this->add_control('message_placeholder', [
            'label' => __('Message Placeholder', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Ucapan',
        ]);

        $this->add_control('submit_text', [
            'label' => __('Submit Button Text', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Kirim',
        ]);

        $this->add_control('label_hadir', [
            'label' => __('Label Hadir', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Hadir',
        ]);

        $this->add_control('label_tidak_hadir', [
            'label' => __('Label Tidak Hadir', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Tidak hadir',
        ]);

        $this->add_control('label_ragu', [
            'label' => __('Label Masih Ragu', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Masih Ragu',
        ]);

        $this->add_control('per_page', [
            'label' => __('Comments Per Page', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 15,
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ]);

        $this->add_control('prev_text', [
            'label' => __('Previous Button Text', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '← Sebelumnya',
        ]);

        $this->add_control('next_text', [
            'label' => __('Next Button Text', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Selanjutnya →',
        ]);

        $this->end_controls_section();

        // === CONTENT: SUCCESS POPUP ===
        $this->start_controls_section('section_popup', [
            'label' => __('Success Popup', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('popup_enable', [
            'label' => __('Enable Popup', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'wisheskit'),
            'label_off' => __('No', 'wisheskit'),
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control('popup_icon', [
            'label' => __('Popup Icon', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::ICONS,
            'default' => [
                'value' => 'fas fa-check-circle',
                'library' => 'fa-solid',
            ],
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_title', [
            'label' => __('Popup Title', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Ucapanmu Sudah Terkirim! 🎉',
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_message', [
            'label' => __('Popup Message', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Terima kasih atas doa dan ucapannya! Mau punya undangan digital seindah ini untuk momen spesialmu? Yuk buat undanganmu sendiri dengan mudah dan cepat.',
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_close_text', [
            'label' => __('Close Button Text', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Nanti Saja',
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        // WhatsApp CTA
        $this->add_control('popup_heading_wa', [
            'label' => __('WhatsApp CTA', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_wa_enable', [
            'label' => __('Show WhatsApp Button', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'wisheskit'),
            'label_off' => __('No', 'wisheskit'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_wa_number', [
            'label' => __('WhatsApp Number', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '6281958404267',
            'description' => __('Format: country code + number (no + or spaces)', 'wisheskit'),
            'condition' => [
                'popup_enable' => 'yes',
                'popup_wa_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_wa_message', [
            'label' => __('WhatsApp Pre-filled Message', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'default' => 'Halo, saya tertarik untuk membuat undangan digital. Boleh info lebih lanjut?',
            'condition' => [
                'popup_enable' => 'yes',
                'popup_wa_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_wa_button_text', [
            'label' => __('WhatsApp Button Text', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Buat Undangan Sekarang',
            'condition' => [
                'popup_enable' => 'yes',
                'popup_wa_enable' => 'yes',
            ],
        ]);

        // Discount Code
        $this->add_control('popup_heading_discount', [
            'label' => __('Discount Code', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_discount_enable', [
            'label' => __('Show Discount Code', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __('Yes', 'wisheskit'),
            'label_off' => __('No', 'wisheskit'),
            'return_value' => 'yes',
            'default' => '',
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_discount_label', [
            'label' => __('Discount Label', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Spesial untukmu:',
            'condition' => [
                'popup_enable' => 'yes',
                'popup_discount_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_discount_code', [
            'label' => __('Discount Code', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'WISH2024',
            'condition' => [
                'popup_enable' => 'yes',
                'popup_discount_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_discount_description', [
            'label' => __('Discount Description', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => 'Pakai kode ini dan dapatkan diskon 10% untuk pembuatan undangan digitalmu',
            'condition' => [
                'popup_enable' => 'yes',
                'popup_discount_enable' => 'yes',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: POPUP ===
        $this->start_controls_section('section_style_popup', [
            'label' => __('Success Popup', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            'condition' => [
                'popup_enable' => 'yes',
            ],
        ]);

        $this->add_control('popup_overlay_color', [
            'label' => __('Overlay Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => 'rgba(0,0,0,0.6)',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-overlay' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_bg_color', [
            'label' => __('Popup Background', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-content' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_border_radius', [
            'label' => __('Popup Border Radius', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => [
                'top' => '16',
                'right' => '16',
                'bottom' => '16',
                'left' => '16',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('popup_icon_color', [
            'label' => __('Icon Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4caf50',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-icon' => 'color: {{VALUE}};',
                '{{WRAPPER}} .wisheskit-popup-icon svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_icon_size', [
            'label' => __('Icon Size', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => [
                'px' => [
                    'min' => 20,
                    'max' => 120,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 60,
            ],
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .wisheskit-popup-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('popup_title_color', [
            'label' => __('Title Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'popup_title_typography',
            'label' => __('Title Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-popup-title',
        ]);

        $this->add_control('popup_message_color', [
            'label' => __('Message Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#555555',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-message' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'popup_message_typography',
            'label' => __('Message Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-popup-message',
        ]);

        $this->add_control('popup_close_bg_color', [
            'label' => __('Close Button Background', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#e0e0e0',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-close-btn' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_close_text_color', [
            'label' => __('Close Button Text Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#333333',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-close-btn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_wa_bg_color', [
            'label' => __('WhatsApp Button Background', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#25d366',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-wa-btn' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_wa_text_color', [
            'label' => __('WhatsApp Button Text Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-wa-btn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_discount_bg_color', [
            'label' => __('Discount Box Background', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f5f5f5',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-discount' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_discount_code_color', [
            'label' => __('Discount Code Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-popup-discount-code' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'popup_discount_code_typography',
            'label' => __('Discount Code Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-popup-discount-code',
        ]);

        $this->end_controls_section();

        // === STYLE: COUNTER BOXES ===
        $this->start_controls_section('section_style_counter', [
            'label' => __('Counter Boxes', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('counter_bg_color', [
            'label' => __('Background Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-counter-box' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('counter_text_color', [
            'label' => __('Text Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-counter-box' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('counter_border_color', [
            'label' => __('Border Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-counter-box' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'counter_number_typography',
            'label' => __('Number Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-counter-number',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'counter_label_typography',
            'label' => __('Label Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-counter-label',
        ]);

        $this->end_controls_section();

        // === STYLE: FORM ===
        $this->start_controls_section('section_style_form', [
            'label' => __('Form', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('input_bg_color', [
            'label' => __('Input Background', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-form input, {{WRAPPER}} .wisheskit-form textarea, {{WRAPPER}} .wisheskit-form select' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('input_text_color', [
            'label' => __('Input Text Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#333333',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-form input, {{WRAPPER}} .wisheskit-form textarea, {{WRAPPER}} .wisheskit-form select' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('input_border_color', [
            'label' => __('Input Border Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#e0e0e0',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-form input, {{WRAPPER}} .wisheskit-form textarea, {{WRAPPER}} .wisheskit-form select' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('input_border_radius', [
            'label' => __('Input Border Radius', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => [
                'top' => '25',
                'right' => '25',
                'bottom' => '25',
                'left' => '25',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .wisheskit-form input, {{WRAPPER}} .wisheskit-form textarea, {{WRAPPER}} .wisheskit-form select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'input_typography',
            'label' => __('Input Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-form input, {{WRAPPER}} .wisheskit-form textarea, {{WRAPPER}} .wisheskit-form select',
        ]);

        $this->end_controls_section();

        // === STYLE: BUTTON ===
        $this->start_controls_section('section_style_button', [
            'label' => __('Submit Button', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('button_bg_color', [
            'label' => __('Background Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-submit-btn' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_text_color', [
            'label' => __('Text Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-submit-btn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('button_border_radius', [
            'label' => __('Border Radius', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => [
                'top' => '25',
                'right' => '25',
                'bottom' => '25',
                'left' => '25',
                'unit' => 'px',
            ],
            'selectors' => [
                '{{WRAPPER}} .wisheskit-submit-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'button_typography',
            'label' => __('Button Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-submit-btn',
        ]);

        $this->add_control('button_padding', [
            'label' => __('Padding', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors' => [
                '{{WRAPPER}} .wisheskit-submit-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: TITLE ===
        $this->start_controls_section('section_style_title', [
            'label' => __('Title', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('title_color', [
            'label' => __('Title Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'title_typography',
            'label' => __('Title Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-title',
        ]);

        $this->end_controls_section();

        // === STYLE: COMMENTS ===
        $this->start_controls_section('section_style_comments', [
            'label' => __('Comments', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('comment_author_color', [
            'label' => __('Author Name Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-comment-author' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'comment_author_typography',
            'label' => __('Author Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-comment-author',
        ]);

        $this->add_control('comment_text_color', [
            'label' => __('Comment Text Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#444444',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-comment-content' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'comment_text_typography',
            'label' => __('Comment Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-comment-content',
        ]);

        $this->add_control('comment_date_color', [
            'label' => __('Date Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#999999',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-comment-date' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('reply_link_color', [
            'label' => __('Reply Link Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#2e7d32',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-reply-btn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('avatar_bg_color', [
            'label' => __('Avatar Background', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4a90d9',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-avatar' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('avatar_text_color', [
            'label' => __('Avatar Text Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-avatar' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('rsvp_badge_hadir_color', [
            'label' => __('Badge Hadir Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#4caf50',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-badge-hadir' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('rsvp_badge_tidak_color', [
            'label' => __('Badge Tidak Hadir Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#f44336',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-badge-tidak_hadir' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('rsvp_badge_ragu_color', [
            'label' => __('Badge Ragu Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ff9800',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-badge-ragu' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        // === STYLE: PAGINATION ===
        $this->start_controls_section('section_style_pagination', [
            'label' => __('Pagination', 'wisheskit'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('pagination_btn_bg', [
            'label' => __('Button Background', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#1a1a1a',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-page-btn' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('pagination_btn_color', [
            'label' => __('Button Text Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-page-btn' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('pagination_info_color', [
            'label' => __('Page Info Color', 'wisheskit'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#555555',
            'selectors' => [
                '{{WRAPPER}} .wisheskit-page-info' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'pagination_typography',
            'label' => __('Typography', 'wisheskit'),
            'selector' => '{{WRAPPER}} .wisheskit-pagination',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = get_the_ID();
        ?>
        <div class="wisheskit-wrapper" data-post-id="<?php echo esc_attr($post_id); ?>" data-per-page="<?php echo esc_attr(intval($settings['per_page'] ?? 15)); ?>">
            <!-- Title -->
            <div class="wisheskit-title">
                <span class="wisheskit-total-count">0</span> <?php echo esc_html($settings['title_text']); ?>
            </div>

            <!-- Counter Boxes -->
            <div class="wisheskit-counters">
                <div class="wisheskit-counter-box">
                    <span class="wisheskit-counter-number wisheskit-count-hadir">0</span>
                    <span class="wisheskit-counter-label"><?php echo esc_html($settings['label_hadir']); ?></span>
                </div>
                <div class="wisheskit-counter-box">
                    <span class="wisheskit-counter-number wisheskit-count-tidak_hadir">0</span>
                    <span class="wisheskit-counter-label"><?php echo esc_html($settings['label_tidak_hadir']); ?></span>
                </div>
                <div class="wisheskit-counter-box">
                    <span class="wisheskit-counter-number wisheskit-count-ragu">0</span>
                    <span class="wisheskit-counter-label"><?php echo esc_html($settings['label_ragu']); ?></span>
                </div>
            </div>

            <!-- Form -->
            <div class="wisheskit-form">
                <input type="text" class="wisheskit-input-name" placeholder="<?php echo esc_attr($settings['name_placeholder']); ?>" />
                <textarea class="wisheskit-input-message" placeholder="<?php echo esc_attr($settings['message_placeholder']); ?>" rows="4"></textarea>
                <select class="wisheskit-input-rsvp">
                    <option value="">Konfirmasi Kehadiran</option>
                    <option value="hadir"><?php echo esc_html($settings['label_hadir']); ?></option>
                    <option value="tidak_hadir"><?php echo esc_html($settings['label_tidak_hadir']); ?></option>
                    <option value="ragu"><?php echo esc_html($settings['label_ragu']); ?></option>
                </select>
                <button type="button" class="wisheskit-submit-btn"><?php echo esc_html($settings['submit_text']); ?></button>
                <div class="wisheskit-form-message"></div>
            </div>

            <!-- Comments List -->
            <div class="wisheskit-comments-list"></div>

            <!-- Pagination -->
            <div class="wisheskit-pagination" style="display:none;">
                <button type="button" class="wisheskit-page-btn wisheskit-page-prev" data-prev-text="<?php echo esc_attr($settings['prev_text']); ?>"><?php echo esc_html($settings['prev_text']); ?></button>
                <span class="wisheskit-page-info">
                    <span class="wisheskit-current-page">1</span> / <span class="wisheskit-total-pages">1</span>
                </span>
                <button type="button" class="wisheskit-page-btn wisheskit-page-next" data-next-text="<?php echo esc_attr($settings['next_text']); ?>"><?php echo esc_html($settings['next_text']); ?></button>
            </div>

            <!-- Success Popup -->
            <?php if ($settings['popup_enable'] === 'yes') : ?>
            <div class="wisheskit-popup-overlay" style="display:none;">
                <div class="wisheskit-popup-content">
                    <div class="wisheskit-popup-icon">
                        <?php \Elementor\Icons_Manager::render_icon($settings['popup_icon'], ['aria-hidden' => 'true']); ?>
                    </div>
                    <div class="wisheskit-popup-title"><?php echo esc_html($settings['popup_title']); ?></div>
                    <div class="wisheskit-popup-message"><?php echo esc_html($settings['popup_message']); ?></div>

                    <?php if ($settings['popup_discount_enable'] === 'yes') : ?>
                    <div class="wisheskit-popup-discount">
                        <div class="wisheskit-popup-discount-label"><?php echo esc_html($settings['popup_discount_label']); ?></div>
                        <div class="wisheskit-popup-discount-code-wrapper">
                            <span class="wisheskit-popup-discount-code"><?php echo esc_html($settings['popup_discount_code']); ?></span>
                            <button type="button" class="wisheskit-popup-copy-btn" data-code="<?php echo esc_attr($settings['popup_discount_code']); ?>" title="Copy">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            </button>
                        </div>
                        <?php if (!empty($settings['popup_discount_description'])) : ?>
                        <div class="wisheskit-popup-discount-desc"><?php echo esc_html($settings['popup_discount_description']); ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($settings['popup_wa_enable'] === 'yes') : ?>
                    <a href="https://wa.me/<?php echo esc_attr($settings['popup_wa_number']); ?>?text=<?php echo rawurlencode($settings['popup_wa_message']); ?>" target="_blank" rel="noopener noreferrer" class="wisheskit-popup-wa-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        <?php echo esc_html($settings['popup_wa_button_text']); ?>
                    </a>
                    <?php endif; ?>

                    <button type="button" class="wisheskit-popup-close-btn"><?php echo esc_html($settings['popup_close_text']); ?></button>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function content_template() {
        ?>
        <div class="wisheskit-wrapper">
            <div class="wisheskit-title">
                <span class="wisheskit-total-count">0</span> {{{ settings.title_text }}}
            </div>
            <div class="wisheskit-counters">
                <div class="wisheskit-counter-box">
                    <span class="wisheskit-counter-number">0</span>
                    <span class="wisheskit-counter-label">{{{ settings.label_hadir }}}</span>
                </div>
                <div class="wisheskit-counter-box">
                    <span class="wisheskit-counter-number">0</span>
                    <span class="wisheskit-counter-label">{{{ settings.label_tidak_hadir }}}</span>
                </div>
                <div class="wisheskit-counter-box">
                    <span class="wisheskit-counter-number">0</span>
                    <span class="wisheskit-counter-label">{{{ settings.label_ragu }}}</span>
                </div>
            </div>
            <div class="wisheskit-form">
                <input type="text" placeholder="{{{ settings.name_placeholder }}}" />
                <textarea placeholder="{{{ settings.message_placeholder }}}" rows="4"></textarea>
                <select>
                    <option value="">Konfirmasi Kehadiran</option>
                    <option value="hadir">{{{ settings.label_hadir }}}</option>
                    <option value="tidak_hadir">{{{ settings.label_tidak_hadir }}}</option>
                    <option value="ragu">{{{ settings.label_ragu }}}</option>
                </select>
                <button type="button" class="wisheskit-submit-btn">{{{ settings.submit_text }}}</button>
            </div>
            <div class="wisheskit-comments-list">
                <div class="wisheskit-comment">
                    <div class="wisheskit-avatar">T</div>
                    <div class="wisheskit-comment-body">
                        <span class="wisheskit-comment-author">Contoh User</span>
                        <span class="wisheskit-badge-hadir">&#10003;</span>
                        <div class="wisheskit-comment-content">Ini adalah contoh ucapan.</div>
                        <div class="wisheskit-comment-meta">
                            <span class="wisheskit-comment-date">1 menit lalu</span>
                            <span class="wisheskit-reply-btn">Reply</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
