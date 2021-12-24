<?php

namespace Tests\Feature;

use Tests\TestCase;

class PushTest extends TestCase
{
    /** @test */
    public function is_it_successfully_send_push_message()
    {
        $response = $this->post('/api/send_push', [
            'title'   => '상품 바로가기 테스트',
            'message' => '상품ID : 1103',
            'image'   => 'https://cdn1.byapps.co.kr/push/202112/10minute1640161700.png',
            'data'    => json_encode(['name' => 'productDetail', 'id' => 1103]),
        ]);

        $response->assertStatus(200);

        dump($response->getContent());
    }
}
