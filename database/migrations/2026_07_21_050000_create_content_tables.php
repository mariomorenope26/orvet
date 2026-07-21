<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajustes generales del sitio (fila única, id = 1)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name')->default('Orvet');
            $table->string('logo')->nullable();
            $table->string('tagline')->nullable();

            // Contacto institucional
            $table->string('address')->nullable();
            $table->string('phone_fixed')->nullable();
            $table->string('phone_mobile')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('schedule_weekday')->nullable();
            $table->string('schedule_saturday')->nullable();
            $table->text('map_embed')->nullable();

            // Redes sociales
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('pinterest')->nullable();

            // Textos "Nosotros"
            $table->longText('about_history')->nullable();
            $table->longText('mission')->nullable();
            $table->longText('vision')->nullable();
            $table->longText('company_values')->nullable();

            // Footer y legales
            $table->text('footer_about')->nullable();
            $table->longText('privacy_policy')->nullable();
            $table->longText('terms')->nullable();

            // Configuración
            $table->string('primary_color')->default('#1f8f4e');
            $table->string('checkout_mode')->default('quote'); // quote | ecommerce
            $table->timestamps();
        });

        // Slider hero de la home
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Marcas / laboratorios representados
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Galería (equipo e institucional)
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image');
            $table->string('type')->default('team'); // team | institutional
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Blog
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });

        // Mensajes del formulario de contacto
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('gallery_images');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('slides');
        Schema::dropIfExists('settings');
    }
};
