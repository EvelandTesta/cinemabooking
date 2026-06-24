<?php
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private $baseUrl = "http://localhost/cinema_booking/api"; // Sesuaikan folder di localhost lo

    // 1. Test Users API
    public function testUsersApi() {
        $ch = curl_init($this->baseUrl . "/users.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertContains($code, [200, 404, 400]);
    }

    // 2. Test Movies API
    public function testMoviesApi() {
        $ch = curl_init($this->baseUrl . "/movies.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertEquals(200, $code);
    }

    // 3. Test Cinemas API
    public function testCinemasApi() {
        $ch = curl_init($this->baseUrl . "/cinemas.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertEquals(200, $code);
    }

    // 4. Test Studios API
    public function testStudiosApi() {
        $ch = curl_init($this->baseUrl . "/studios.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertEquals(200, $code);
    }

    // 5. Test Seats API
    public function testSeatsApi() {
        $ch = curl_init($this->baseUrl . "/seats.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertEquals(200, $code);
    }

    // 6. Test Showtimes API
    public function testShowtimesApi() {
        $ch = curl_init($this->baseUrl . "/showtimes.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertEquals(200, $code);
    }

    // 7. Test Bookings API
    public function testBookingsApi() {
        $ch = curl_init($this->baseUrl . "/bookings.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertEquals(200, $code);
    }

    // 8. Test Payments API
    public function testPaymentsApi() {
        $ch = curl_init($this->baseUrl . "/payments.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertContains($code, [200, 404]);
    }

    // 9. Test Seat Availability API
    public function testSeatAvailabilityApi() {
        $ch = curl_init($this->baseUrl . "/seat_availability.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $this->assertEquals(200, $code);
    }
}