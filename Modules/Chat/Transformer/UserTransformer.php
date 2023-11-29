<?php
namespace Modules\Chat\Transformer;

class UserTransformer {

    public static function collection($users)
    {
        $transformedUsers = $users->map(function ($user) {
            return self::transform($user);
        });

        return [
            $transformedUsers,
            [
                'last_page' => $users->lastPage(),
                'next_page_url' => $users->nextPageUrl(),
                'prev_page_url' => $users->previousPageUrl(),
                'total' => $users->total()
            ]
        ];
    }

    public static function transform($user)
    {
        if (!$user) {
            return null;
        };

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'username' => $user->username,
//            'profile' => ProfileTransformer::transform($user->profile),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }


}
