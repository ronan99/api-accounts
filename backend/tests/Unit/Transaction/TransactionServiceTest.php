<?php

use App\Enums\UserType;
use App\Helpers\NotifyMessage;

use App\Jobs\TransactionDispatcher;
use App\Models\User;
use App\Repository\Contracts\Transaction\ITransactionRepository;
use App\Repository\Contracts\User\IUserRepository;
use App\Services\Transaction\TransactionService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Queue;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\TestCase::class);

test('transaction should succeed', function () {

    Queue::fake();
    Queue::assertNothingPushed();
    $userFrom = User::factory()->state(['balance' => 100000, 'type' => UserType::PERSON])->create();
    $userTo = User::factory()->create();


    $usrRepoMock = Mockery::mock(IUserRepository::class);
    $usrRepoMock->shouldReceive("findAndLock")->andReturn($userFrom);
    $usrRepoMock->shouldReceive("decrementBalance")->andReturn(true);

    $dbRepoMock = Mockery::mock(DatabaseManager::class);
    $dbRepoMock->shouldReceive("beginTransaction")->once();
    $dbRepoMock->shouldReceive("commit")->once();
    $dbRepoMock->shouldReceive("rollback");

    Http::fake(function ($request) {
        return Http::response(["message" => "Autorizado"], 200);
    });

    $ntfRepoMock = Mockery::mock(NotifyMessage::class);

    $trRepoMock = Mockery::mock(ITransactionRepository::class);

    $transactionService = new TransactionService($trRepoMock, $usrRepoMock, $dbRepoMock, $ntfRepoMock);

    $res = $transactionService->transferBalance($userFrom['id'], $userTo['id'], 10000 );

    Queue::assertPushed(TransactionDispatcher::class);

    expect($res)->toBe(['success' => true, 'message' => "Saldo transferido"]);

});
