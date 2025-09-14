package com.example.demo.domain.usecase.user;

import java.util.List;
import com.example.demo.domain.entities.user.IUserRepository;
import com.example.demo.domain.entities.user.User;

public class SearchUserByEmail {
    private final IUserRepository userRepository;

    public SearchUserByEmail(IUserRepository userRepository) {
        this.userRepository = userRepository;
    }

    public Optional<User> execute(String email) {
        return userRepository.findByEmail(email);
    }
}
