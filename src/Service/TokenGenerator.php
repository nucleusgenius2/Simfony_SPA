<?php
namespace App\Service;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\User;
class TokenGenerator
{
    private $jwtManager;
    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }
    public function createToken(User $user): string
    {
        return $this->jwtManager->create($user);
    }
}
