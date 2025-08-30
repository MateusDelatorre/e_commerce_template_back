package com.example.demo.domain.usecase.user;

import java.util.List;
import com.example.demo.domain.entities.user.IUserRepository;
import com.example.demo.domain.entities.user.User;

public class SearchUserByUserName {
    private final IUserRepository userRepository;

    public SearchUserByUserName(IUserRepository userRepository) {
        this.userRepository = userRepository;
    }

    public List<User> execute(String username) {
        return userRepository.searchByName(username);
    }
}