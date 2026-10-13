<?php

namespace Tests\Unit;

use App\Mail\OtpMail;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Tests\TestCase;

class OtpMailTest extends TestCase
{
    public function test_otp_mail_can_be_instantiated(): void
    {
        $otp = new OtpMail('123456');

        $this->assertInstanceOf(OtpMail::class, $otp);
    }

    public function test_otp_mail_stores_otp_code(): void
    {
        $otpCode = '654321';
        $otp = new OtpMail($otpCode);

        $this->assertEquals($otpCode, $otp->otp_code);
    }

    public function test_otp_mail_envelope_has_correct_subject(): void
    {
        $otp = new OtpMail('123456');
        $envelope = $otp->envelope();

        $this->assertInstanceOf(Envelope::class, $envelope);
        $this->assertEquals(
            'Kode verifikasi kamu sudah siap! (Jangan dibagikan ke siapa pun ya)',
            $envelope->subject
        );
    }

    public function test_otp_mail_content_returns_content_instance(): void
    {
        $otp = new OtpMail('123456');
        $content = $otp->content();

        $this->assertInstanceOf(Content::class, $content);
    }

    public function test_otp_mail_content_uses_correct_view(): void
    {
        $otp = new OtpMail('123456');
        $content = $otp->content();

        $this->assertEquals('mail.otp', $content->view);
    }

    public function test_otp_mail_content_passes_otp_code_to_view(): void
    {
        $otpCode = '999888';
        $otp = new OtpMail($otpCode);
        $content = $otp->content();

        // Verify that the content has the 'with' data
        $this->assertIsArray($content->with);
        $this->assertArrayHasKey('otp_code', $content->with);
        $this->assertEquals($otpCode, $content->with['otp_code']);
    }

    public function test_otp_mail_attachments_returns_array(): void
    {
        $otp = new OtpMail('123456');
        $attachments = $otp->attachments();

        $this->assertIsArray($attachments);
        $this->assertEmpty($attachments);
    }

    public function test_otp_mail_with_different_otp_codes(): void
    {
        $codes = ['000000', '111111', '999999', '123456'];

        foreach ($codes as $code) {
            $otp = new OtpMail($code);
            $this->assertEquals($code, $otp->otp_code);
        }
    }

    public function test_otp_mail_subject_is_in_indonesian(): void
    {
        $otp = new OtpMail('123456');
        $envelope = $otp->envelope();

        $this->assertStringContainsString('verifikasi', $envelope->subject);
        $this->assertStringContainsString('siap', $envelope->subject);
    }
}
