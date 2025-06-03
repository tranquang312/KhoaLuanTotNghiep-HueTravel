use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tour_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guide_id')->constrained('users')->onDelete('cascade'); // Guide là user có role 'guide'
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            // Đảm bảo mỗi booking chỉ có một guide
            $table->unique('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_guides');
    }
}; 