package com.example.demo.domain.usecase.user;

import java.util.List;
import com.example.demo.domain.entities.user.IUserRepository;
import com.example.demo.domain.entities.user.User;

public class SearchUserById {
    private final IUserRepository userRepository;

    public SearchUserById(IUserRepository userRepository) {
        this.userRepository = userRepository;
    }

    public Optional<User> findById(Long id) {
        return userRepository.findById(id);
    }
}
