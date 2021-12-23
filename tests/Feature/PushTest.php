<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PushTest extends TestCase
{
    /** @test */
    public function is_it_successfully_send_push_message()
    {
        $response = $this->post('/api/send_push', [
            'title' => '테스트 푸시',
            'message' => '이 편지는 영국에서 최초로 시작되어 일년에 한바퀴 돌면서 받는 사람에게 행운을 주었고 지금은 당신에게로 옮겨진 이 편지는 4일 안에 당신 곁을 떠나야 합니다.',
            'users' => [7]
        ]);

        $response->assertStatus(200);
    }
}
