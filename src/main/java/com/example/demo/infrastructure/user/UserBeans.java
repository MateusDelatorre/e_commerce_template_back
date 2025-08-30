package com.example.demo.infrastructure.user;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import com.example.demo.data.repositories.UserRepository;
import com.example.demo.domain.usecase.user.CreateUserUseCase;
import com.example.demo.domain.usecase.user.SearchUserByUserName;

@Configuration
public class UserBeans {

    // Define User-related beans here

    @Bean
    public CreateUserUseCase createUserUseCase(UserRepository userRepository) {
        UserDatabaseGateway userDatabaseGateway = new UserDatabaseGateway(userRepository);
        return new CreateUserUseCase(userDatabaseGateway);
    }

    @Bean
    public SearchUserByUserName searchUserUseCase(UserRepository userRepository) {
        UserDatabaseGateway userDatabaseGateway = new UserDatabaseGateway(userRepository);
        return new SearchUserByUserName(userDatabaseGateway);
    }
}

