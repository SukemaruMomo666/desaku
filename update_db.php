<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$type = App\Models\LetterType::where('code', 'SKTM')->first();
if($type && $type->template_file) {
    $controller = new App\Http\Controllers\AdminLetterTypeController;
    $reflection = new ReflectionMethod($controller, 'extractFormFieldsFromDocx');
    $reflection->setAccessible(true);
    
    $fields = $reflection->invoke($controller, storage_path('app/public/' . $type->template_file));
    $type->form_fields = $fields;
    $type->save();
    echo 'Done: ' . json_encode($fields);
} else {
    echo "SKTM not found or no template.";
}
